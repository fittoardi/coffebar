@extends('layouts.admin')

@section('title', 'Katalog Produk & Menu')

@section('content')
<div class="space-y-6">
    <!-- Header (03-DESIGN.md Section 19) -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-dashed border-[#CDB48F] pb-4 gap-4">
        <div>
            <span class="text-xs uppercase tracking-widest text-[#B5753A] font-bold block">Katalog Menu & Racikan</span>
            <h1 class="text-3xl font-display font-semibold text-[#2B1D14] tracking-tight mt-1">Produk & Menu</h1>
        </div>
        <div>
            <a href="{{ route('admin.products.create') }}"
               class="px-5 py-2.5 bg-[#B5753A] text-white text-xs uppercase tracking-wider font-bold rounded-xl shadow-warm hover:bg-[#B4552F] transition inline-flex items-center space-x-2">
                <span>+</span>
                <span>Tambah Produk Baru</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <form method="GET" action="{{ route('admin.products.index') }}"
          class="bg-[#FFFDF8] border border-[#CDB48F] p-4 rounded-2xl shadow-sm flex flex-col md:flex-row gap-3 items-stretch md:items-center justify-between text-xs">
        <div class="flex-1 flex flex-col sm:flex-row gap-3">
            <!-- Search -->
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama produk atau deskripsi..."
                       class="w-full bg-[#FFFDF8] border border-[#CDB48F] px-3.5 py-2.5 text-xs text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">
            </div>

            <!-- Category -->
            <div class="sm:w-48">
                <select name="category_id"
                        class="w-full bg-[#FFFDF8] border border-[#CDB48F] px-3.5 py-2.5 text-xs text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none font-semibold">
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
                        class="w-full bg-[#FFFDF8] border border-[#CDB48F] px-3.5 py-2.5 text-xs text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none font-semibold">
                    <option value="">-- Status Stok --</option>
                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="unavailable" {{ request('status') === 'unavailable' ? 'selected' : '' }}>Habis (Sold Out)</option>
                </select>
            </div>
        </div>

        <div class="flex items-center space-x-2">
            <button type="submit"
                    class="px-5 py-2.5 bg-[#4A3224] text-[#F4ECDD] text-xs uppercase tracking-wider font-bold rounded-xl hover:bg-[#2B1D14] transition cursor-pointer">
                Filter
            </button>
            @if (request()->hasAny(['search', 'category_id', 'status']))
                <a href="{{ route('admin.products.index') }}"
                   class="px-3.5 py-2.5 border border-[#CDB48F] text-[#6B5646] hover:text-[#2B1D14] text-xs rounded-xl transition">
                    Reset
                </a>
            @endif
        </div>
    </form>

    <!-- Table of Products (Oat Header, Paper rows, Kraft border) -->
    <div class="bg-[#FFFDF8] border border-[#CDB48F] rounded-2xl shadow-sm overflow-hidden">
        @if ($products->isEmpty())
            <div class="p-8 text-center text-[#6B5646] text-sm">
                Tidak ada produk ditemukan. Coba sesuaikan kata kunci filter atau tambah produk baru.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-[#E8DCC4] uppercase tracking-wider text-[#4A3224] text-[10px] font-bold">
                            <th class="p-3.5">Produk</th>
                            <th class="p-3.5">Kategori</th>
                            <th class="p-3.5 text-right">Harga Dasar</th>
                            <th class="p-3.5 text-center">Varian / Opsi</th>
                            <th class="p-3.5 text-center">Ketersediaan</th>
                            <th class="p-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dashed divide-[#CDB48F]/60">
                        @foreach ($products as $prod)
                            <tr class="hover:bg-[#F4ECDD]/50 transition">
                                <td class="p-3.5">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-[#F4ECDD] border border-[#CDB48F] rounded-xl overflow-hidden shrink-0 flex items-center justify-center p-1">
                                            @if ($prod->image_url)
                                                <img src="{{ $prod->image_url }}" alt="{{ $prod->name }}" class="w-full h-full object-contain">
                                            @else
                                                <span class="text-xl select-none">
                                                    @if (str_contains(strtolower($prod->name), 'croissant') || str_contains(strtolower($prod->name), 'roti'))
                                                        🥐
                                                    @elseif (str_contains(strtolower($prod->name), 'nasi'))
                                                        🍲
                                                    @elseif (str_contains(strtolower($prod->name), 'fries'))
                                                        🍟
                                                    @else
                                                        🍽️
                                                    @endif
                                                </span>
                                            @endif
                                        </div>
                                        <div>
                                            <strong class="font-display font-semibold text-sm text-[#2B1D14] block">{{ $prod->name }}</strong>
                                            <span class="text-[11px] font-mono text-[#6B5646]">{{ $prod->slug }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-3.5 text-[#4A3224]">
                                    <span class="px-2.5 py-1 bg-[#E8DCC4] border border-[#CDB48F] rounded-md text-[11px] font-semibold">
                                        {{ $prod->category ? $prod->category->name : 'Tanpa Kategori' }}
                                    </span>
                                </td>
                                <td class="p-3.5 text-right font-display font-bold text-[#B5753A] text-sm">
                                    Rp {{ number_format($prod->price, 0, ',', '.') }}
                                </td>
                                <td class="p-3.5 text-center text-[11px]">
                                    @if ($prod->options->count() > 0)
                                        <span class="px-2.5 py-1 bg-[#E8DCC4]/60 border border-[#CDB48F] rounded-md text-[#2B1D14] font-bold">
                                            {{ $prod->options->count() }} opsi
                                        </span>
                                    @else
                                        <span class="text-[#6B5646]/60">—</span>
                                    @endif
                                </td>
                                <td class="p-3.5 text-center">
                                    <form method="POST" action="{{ route('admin.products.toggle', $prod) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                title="Klik untuk mengubah ketersediaan produk"
                                                class="px-3 py-1 border text-[10px] uppercase font-bold rounded-full transition cursor-pointer {{ $prod->is_available ? 'bg-[#7A8467]/20 text-[#4F5A3E] border-[#7A8467]' : 'bg-[#B4552F]/15 text-[#B4552F] border-[#B4552F]' }}">
                                            {{ $prod->is_available ? '✓ Tersedia' : '✕ Habis' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="p-3.5 text-right space-x-2">
                                    <a href="{{ route('admin.products.edit', $prod) }}"
                                       class="px-3 py-1.5 bg-[#FFFDF8] text-[#4A3224] border border-[#CDB48F] text-[11px] font-bold uppercase rounded-lg hover:bg-[#E8DCC4] transition">
                                        Ubah
                                    </a>

                                    <form method="POST" action="{{ route('admin.products.destroy', $prod) }}" class="inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1.5 bg-transparent text-[#B4552F] border border-[#B4552F] text-[11px] font-bold uppercase rounded-lg hover:bg-[#B4552F] hover:text-white transition cursor-pointer">
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
                <div class="p-4 border-t border-dashed border-[#CDB48F]">
                    {{ $products->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
