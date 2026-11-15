@extends('layouts.admin')

@section('title', 'Tambah Kategori Baru')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="border-b-2 border-copper/30 pb-4">
        <a href="{{ route('admin.categories.index') }}" class="text-xs font-heading text-copper uppercase tracking-wider hover:underline block mb-1">
            ← Kembali ke Daftar Kategori
        </a>
        <h1 class="text-2xl font-display font-black text-rustic-900 tracking-tight">Tambah Kategori Baru</h1>
    </div>

    <form method="POST" action="{{ route('admin.categories.store') }}"
          class="bg-[#FFFDF9] border-2 border-rustic-300 p-6 shadow-rustic-sm space-y-5">
        @csrf

        <!-- Nama Kategori -->
        <div>
            <label class="block text-xs font-heading uppercase tracking-wider text-rustic-700 font-bold mb-1">
                Nama Kategori <span class="text-brick">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   placeholder="Contoh: Kopi Klasik, Manual Brew, Makanan Ringan"
                   class="w-full bg-parchment-light border-2 border-rustic-300 p-2.5 text-sm font-body text-rustic-900 focus:border-copper focus:outline-none">
        </div>

        <!-- Slug (Opsional) -->
        <div>
            <label class="block text-xs font-heading uppercase tracking-wider text-rustic-700 font-bold mb-1">
                Slug URL (Opsional)
            </label>
            <input type="text" name="slug" value="{{ old('slug') }}"
                   placeholder="Biarkan kosong untuk otomatis dari nama kategori"
                   class="w-full bg-parchment-light border-2 border-rustic-300 p-2.5 text-sm font-mono text-rustic-900 focus:border-copper focus:outline-none">
            <p class="text-[11px] text-rustic-500 mt-1">Digunakan untuk URL kategori, contoh: `kopi-klasik`</p>
        </div>

        <!-- Deskripsi -->
        <div>
            <label class="block text-xs font-heading uppercase tracking-wider text-rustic-700 font-bold mb-1">
                Deskripsi Singkat
            </label>
            <textarea name="description" rows="3"
                      placeholder="Penjelasan mengenai kelompok menu ini..."
                      class="w-full bg-parchment-light border-2 border-rustic-300 p-2.5 text-sm font-body text-rustic-900 focus:border-copper focus:outline-none">{{ old('description') }}</textarea>
        </div>

        <!-- Icon Emoji & Urutan -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-heading uppercase tracking-wider text-rustic-700 font-bold mb-1">
                    Ikon Emoji (Opsional)
                </label>
                <input type="text" name="icon" value="{{ old('icon', '☕') }}"
                       placeholder="☕, 🍵, 🥐, 🧃"
                       class="w-full bg-parchment-light border-2 border-rustic-300 p-2.5 text-sm text-center font-body text-rustic-900 focus:border-copper focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-heading uppercase tracking-wider text-rustic-700 font-bold mb-1">
                    Urutan Tampil (Sort Order)
                </label>
                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                       class="w-full bg-parchment-light border-2 border-rustic-300 p-2.5 text-sm font-mono text-rustic-900 focus:border-copper focus:outline-none">
            </div>
        </div>

        <!-- Checkbox Status Aktif -->
        <div class="pt-2 border-t border-rustic-200">
            <label class="inline-flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                       class="w-4 h-4 text-copper border-rustic-400 rounded focus:ring-copper">
                <span class="text-xs font-heading uppercase tracking-wider text-rustic-800 font-bold">
                    Aktifkan Kategori (Tampil di Menu Pelanggan)
                </span>
            </label>
        </div>

        <!-- Submit Button -->
        <div class="pt-4 flex items-center justify-end space-x-3">
            <a href="{{ route('admin.categories.index') }}"
               class="px-4 py-2 border border-rustic-300 text-rustic-700 font-heading text-xs uppercase tracking-wider hover:bg-rustic-100 transition">
                Batal
            </a>
            <button type="submit"
                    class="px-5 py-2.5 bg-copper text-parchment font-heading text-xs uppercase tracking-wider font-bold shadow-rustic-sm hover:bg-copper-dark transition">
                Simpan Kategori
            </button>
        </div>
    </form>
</div>
@endsection
