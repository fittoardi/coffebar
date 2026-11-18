@extends('layouts.admin')

@section('title', 'Ubah Kategori — ' . $category->name)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="border-b border-dashed border-[#CDB48F] pb-4">
        <a href="{{ route('admin.categories.index') }}" class="text-xs text-[#B5753A] font-bold uppercase tracking-wider hover:underline block mb-1">
            ← Kembali ke Daftar Kategori
        </a>
        <h1 class="text-2xl font-display font-semibold text-[#2B1D14] tracking-tight">Ubah Kategori: {{ $category->name }}</h1>
    </div>

    <form method="POST" action="{{ route('admin.categories.update', $category) }}"
          class="bg-[#FFFDF8] border border-[#CDB48F] p-6 sm:p-8 rounded-2xl shadow-sm space-y-5">
        @csrf
        @method('PUT')

        <!-- Nama Kategori -->
        <div>
            <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                Nama Kategori <span class="text-[#B4552F]">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                   class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">
        </div>

        <!-- Slug -->
        <div>
            <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                Slug URL
            </label>
            <input type="text" name="slug" value="{{ old('slug', $category->slug) }}"
                   class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm font-mono text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">
            <p class="text-[11px] text-[#6B5646] mt-1">Digunakan untuk URL kategori, contoh: `kopi-klasik`</p>
        </div>

        <!-- Deskripsi -->
        <div>
            <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                Deskripsi Singkat
            </label>
            <textarea name="description" rows="3"
                      class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">{{ old('description', $category->description) }}</textarea>
        </div>

        <!-- Icon Emoji & Urutan -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                    Ikon Emoji (Opsional)
                </label>
                <input type="text" name="icon" value="{{ old('icon', $category->icon) }}"
                       class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm text-center text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">
            </div>

            <div>
                <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                    Urutan Tampil (Sort Order)
                </label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0"
                       class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm font-mono text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">
            </div>
        </div>

        <!-- Checkbox Status Aktif -->
        <div class="pt-3 border-t border-dashed border-[#CDB48F]">
            <label class="inline-flex items-center space-x-2.5 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                       class="w-4 h-4 text-[#B5753A] border-[#CDB48F] rounded focus:ring-[#B5753A]">
                <span class="text-xs uppercase tracking-wider text-[#2B1D14] font-bold">
                    Aktifkan Kategori (Tampil di Menu Pelanggan)
                </span>
            </label>
        </div>

        <!-- Submit Button -->
        <div class="pt-4 flex items-center justify-end space-x-3">
            <a href="{{ route('admin.categories.index') }}"
               class="px-5 py-2.5 border border-[#CDB48F] text-[#4A3224] text-xs uppercase tracking-wider font-semibold rounded-xl hover:bg-[#E8DCC4] transition">
                Batal
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-[#B5753A] text-white text-xs uppercase tracking-wider font-bold rounded-xl shadow-warm hover:bg-[#B4552F] transition cursor-pointer">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
