@extends('layouts.admin')

@section('title', 'Kategori Menu')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b-2 border-copper/30 pb-4 gap-4">
        <div>
            <span class="text-xs uppercase font-heading tracking-widest text-copper block">Struktur Menu Kedai</span>
            <h1 class="text-3xl font-display font-black text-rustic-900 tracking-tight">Kategori Menu</h1>
        </div>
        <div>
            <a href="{{ route('admin.categories.create') }}"
               class="px-4 py-2.5 bg-copper text-parchment font-heading text-xs uppercase tracking-wider font-bold shadow-rustic-sm hover:bg-copper-dark transition inline-flex items-center space-x-2">
                <span>+</span>
                <span>Tambah Kategori Baru</span>
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-[#FFFDF9] border-2 border-rustic-300 shadow-rustic-sm overflow-hidden">
        @if ($categories->isEmpty())
            <div class="p-8 text-center text-rustic-500 font-heading text-sm">
                Belum ada kategori menu. Klik tombol di atas untuk menambahkan kategori pertama.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b-2 border-rustic-800 bg-rustic-100 font-heading uppercase tracking-wider text-rustic-800">
                            <th class="p-3 w-12 text-center">Urutan</th>
                            <th class="p-3">Nama Kategori</th>
                            <th class="p-3">Slug</th>
                            <th class="p-3">Deskripsi</th>
                            <th class="p-3 text-center">Jumlah Produk</th>
                            <th class="p-3 text-center">Status</th>
                            <th class="p-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-rustic-200">
                        @foreach ($categories as $cat)
                            <tr class="hover:bg-rustic-50/70 transition">
                                <td class="p-3 text-center font-bold text-rustic-600 font-mono">
                                    {{ $cat->sort_order }}
                                </td>
                                <td class="p-3">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-lg">{{ $cat->icon ?: '☕' }}</span>
                                        <strong class="text-rustic-900 font-display text-sm">{{ $cat->name }}</strong>
                                    </div>
                                </td>
                                <td class="p-3 font-mono text-rustic-500 text-[11px]">
                                    {{ $cat->slug }}
                                </td>
                                <td class="p-3 text-rustic-600 max-w-xs truncate">
                                    {{ $cat->description ?: '—' }}
                                </td>
                                <td class="p-3 text-center font-heading font-bold text-rustic-800">
                                    {{ $cat->products_count }} item
                                </td>
                                <td class="p-3 text-center">
                                    @if ($cat->is_active)
                                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 border border-emerald-300 font-heading uppercase text-[10px] font-bold">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 bg-rose-100 text-rose-800 border border-rose-300 font-heading uppercase text-[10px] font-bold">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="p-3 text-right space-x-2">
                                    <a href="{{ route('admin.categories.edit', $cat) }}"
                                       class="px-2.5 py-1 bg-rustic-100 text-rustic-800 border border-rustic-300 font-heading text-[11px] font-bold hover:bg-copper hover:text-parchment hover:border-copper transition">
                                        Ubah
                                    </a>

                                    <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" class="inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
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
        @endif
    </div>
</div>
@endsection
