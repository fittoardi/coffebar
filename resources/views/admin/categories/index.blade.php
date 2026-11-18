@extends('layouts.admin')

@section('title', 'Kategori Menu')

@section('content')
<div class="space-y-6">
    <!-- Header (03-DESIGN.md Section 19) -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-dashed border-[#CDB48F] pb-4 gap-4">
        <div>
            <span class="text-xs uppercase tracking-widest text-[#B5753A] font-bold block">Struktur Menu Kedai</span>
            <h1 class="text-3xl font-display font-semibold text-[#2B1D14] tracking-tight mt-1">Kategori Menu</h1>
        </div>
        <div>
            <a href="{{ route('admin.categories.create') }}"
               class="px-5 py-2.5 bg-[#B5753A] text-white text-xs uppercase tracking-wider font-bold rounded-xl shadow-warm hover:bg-[#B4552F] transition inline-flex items-center space-x-2">
                <span>+</span>
                <span>Tambah Kategori Baru</span>
            </a>
        </div>
    </div>

    <!-- Table Card (Oat Header, Paper body, Kraft border) -->
    <div class="bg-[#FFFDF8] border border-[#CDB48F] rounded-2xl shadow-sm overflow-hidden">
        @if ($categories->isEmpty())
            <div class="p-8 text-center text-[#6B5646] text-sm">
                Belum ada kategori menu. Klik tombol di atas untuk menambahkan kategori pertama.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-[#E8DCC4] uppercase tracking-wider text-[#4A3224] text-[10px] font-bold">
                            <th class="p-3.5 w-12 text-center">Urutan</th>
                            <th class="p-3.5">Nama Kategori</th>
                            <th class="p-3.5">Slug</th>
                            <th class="p-3.5">Deskripsi</th>
                            <th class="p-3.5 text-center">Jumlah Produk</th>
                            <th class="p-3.5 text-center">Status</th>
                            <th class="p-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dashed divide-[#CDB48F]/60">
                        @foreach ($categories as $cat)
                            <tr class="hover:bg-[#F4ECDD]/50 transition">
                                <td class="p-3.5 text-center font-bold text-[#6B5646] font-mono">
                                    {{ $cat->sort_order }}
                                </td>
                                <td class="p-3.5">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-lg">{{ $cat->icon ?: '☕' }}</span>
                                        <strong class="text-[#2B1D14] font-display font-semibold text-sm">{{ $cat->name }}</strong>
                                    </div>
                                </td>
                                <td class="p-3.5 font-mono text-[#6B5646] text-[11px]">
                                    {{ $cat->slug }}
                                </td>
                                <td class="p-3.5 text-[#6B5646] max-w-xs truncate">
                                    {{ $cat->description ?: '—' }}
                                </td>
                                <td class="p-3.5 text-center font-bold text-[#4A3224]">
                                    {{ $cat->products_count }} item
                                </td>
                                <td class="p-3.5 text-center">
                                    @if ($cat->is_active)
                                        <span class="px-2.5 py-0.5 bg-[#7A8467]/20 text-[#4F5A3E] border border-[#7A8467] uppercase text-[10px] font-bold rounded-full">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 bg-[#B4552F]/15 text-[#B4552F] border border-[#B4552F] uppercase text-[10px] font-bold rounded-full">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="p-3.5 text-right space-x-2">
                                    <a href="{{ route('admin.categories.edit', $cat) }}"
                                       class="px-3 py-1.5 bg-[#FFFDF8] text-[#4A3224] border border-[#CDB48F] text-[11px] font-bold uppercase rounded-lg hover:bg-[#E8DCC4] transition">
                                        Ubah
                                    </a>

                                    <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" class="inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
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
        @endif
    </div>
</div>
@endsection
