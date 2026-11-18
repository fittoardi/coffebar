@extends('layouts.admin')

@section('title', 'Tambah Produk Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6"
     x-data="{
         options: [
             { type: 'size', name: 'Regular (12oz)', price: 0, is_available: true },
             { type: 'size', name: 'Large (16oz)', price: 4000, is_available: true }
         ],
         addOption() {
             this.options.push({ type: 'addon', name: '', price: 3000, is_available: true });
         },
         removeOption(index) {
             this.options.splice(index, 1);
         }
     }">
    <div class="border-b border-dashed border-[#CDB48F] pb-4">
        <a href="{{ route('admin.products.index') }}" class="text-xs text-[#B5753A] font-bold uppercase tracking-wider hover:underline block mb-1">
            ← Kembali ke Katalog Produk
        </a>
        <h1 class="text-2xl font-display font-semibold text-[#2B1D14] tracking-tight">Tambah Produk & Menu Baru</h1>
    </div>

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data"
          class="space-y-6">
        @csrf

        <!-- INFORMASI UTAMA (03-DESIGN.md Section 19) -->
        <div class="bg-[#FFFDF8] border border-[#CDB48F] p-6 sm:p-8 rounded-2xl shadow-sm space-y-5">
            <h2 class="font-display font-semibold text-lg text-[#2B1D14] border-b border-dashed border-[#CDB48F] pb-3">
                1. Informasi Utama Produk
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Kategori -->
                <div>
                    <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                        Kategori Menu <span class="text-[#B4552F]">*</span>
                    </label>
                    <select name="category_id" required
                            class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Nama Produk -->
                <div>
                    <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                        Nama Produk <span class="text-[#B4552F]">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="Contoh: Kopi Susu Aren Klasik"
                           class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Slug (Opsional) -->
                <div>
                    <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                        Slug URL (Opsional)
                    </label>
                    <input type="text" name="slug" value="{{ old('slug') }}"
                           placeholder="Biarkan kosong untuk otomatis dari nama"
                           class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm font-mono text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">
                </div>

                <!-- Harga Dasar -->
                <div>
                    <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                        Harga Dasar (Rp) <span class="text-[#B4552F]">*</span>
                    </label>
                    <input type="number" name="price" value="{{ old('price', 18000) }}" min="0" step="500" required
                           class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm font-display font-bold text-[#B5753A] rounded-xl focus:border-[#B5753A] focus:outline-none">
                </div>
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                    Deskripsi & Karakter Rasa
                </label>
                <textarea name="description" rows="3"
                          placeholder="Perpaduan espresso arabika gayo dan susu segar dengan gula aren organik..."
                          class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">{{ old('description') }}</textarea>
            </div>

            <!-- Foto Produk -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                        URL Gambar (Web Link)
                    </label>
                    <input type="url" name="image" value="{{ old('image') }}"
                           placeholder="https://images.unsplash.com/..."
                           class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                        Atau Unggah Berkas Foto
                    </label>
                    <input type="file" name="image_file" accept="image/*"
                           class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-2.5 text-xs text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                        Urutan Tampil (Sort Order)
                    </label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                           class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm font-mono text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">
                </div>

                <div class="flex items-center pt-5">
                    <label class="inline-flex items-center space-x-2.5 cursor-pointer">
                        <input type="checkbox" name="is_available" value="1" {{ old('is_available', true) ? 'checked' : '' }}
                               class="w-4 h-4 text-[#B5753A] border-[#CDB48F] rounded focus:ring-[#B5753A]">
                        <span class="text-xs uppercase tracking-wider text-[#2B1D14] font-bold">
                            Tersedia (Ready Stock)
                        </span>
                    </label>
                </div>
            </div>
        </div>

        <!-- VARIAN & OPSI PRODUK -->
        <div class="bg-[#FFFDF8] border border-[#CDB48F] p-6 sm:p-8 rounded-2xl shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-dashed border-[#CDB48F] pb-3">
                <div>
                    <h2 class="font-display font-semibold text-lg text-[#2B1D14]">
                        2. Opsi & Varian Menu (Opsional)
                    </h2>
                    <p class="text-xs text-[#6B5646]">
                        Tambahkan pilihan ukuran, ekstra shot, sirup rasa, atau topping.
                    </p>
                </div>
                <button type="button" @click="addOption()"
                        class="px-4 py-2 bg-[#4A3224] text-[#F4ECDD] text-xs uppercase tracking-wider font-bold rounded-xl hover:bg-[#2B1D14] transition cursor-pointer">
                    + Tambah Opsi
                </button>
            </div>

            <div class="space-y-3">
                <template x-for="(opt, index) in options" :key="index">
                    <div class="p-4 bg-[#F4ECDD]/50 border border-[#CDB48F] rounded-xl grid grid-cols-1 sm:grid-cols-12 gap-3 items-center text-xs">
                        <!-- Tipe -->
                        <div class="sm:col-span-3">
                            <label class="block text-[10px] uppercase font-bold text-[#6B5646] mb-1">Tipe</label>
                            <select :name="'options[' + index + '][type]'" x-model="opt.type"
                                    class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-2 text-xs text-[#2B1D14] rounded-lg">
                                <option value="size">Ukuran (Size)</option>
                                <option value="addon">Tambahan (Addon)</option>
                                <option value="topping">Topping</option>
                                <option value="sugar">Tingkat Gula</option>
                                <option value="ice">Tingkat Es</option>
                                <option value="base">Bahan Dasar</option>
                            </select>
                        </div>

                        <!-- Nama Opsi -->
                        <div class="sm:col-span-4">
                            <label class="block text-[10px] uppercase font-bold text-[#6B5646] mb-1">Nama Pilihan</label>
                            <input type="text" :name="'options[' + index + '][name]'" x-model="opt.name" required
                                   placeholder="Contoh: Extra Espresso Shot"
                                   class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-2 text-xs text-[#2B1D14] rounded-lg">
                        </div>

                        <!-- Tambahan Harga -->
                        <div class="sm:col-span-3">
                            <label class="block text-[10px] uppercase font-bold text-[#6B5646] mb-1">Harga Tambahan (Rp)</label>
                            <input type="number" :name="'options[' + index + '][price]'" x-model="opt.price" min="0" step="500" required
                                   class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-2 text-xs font-display font-bold text-[#B5753A] rounded-lg">
                        </div>

                        <!-- Aksi Hapus -->
                        <div class="sm:col-span-2 flex items-center justify-end pt-3 sm:pt-0">
                            <input type="hidden" :name="'options[' + index + '][is_available]'" value="1">
                            <button type="button" @click="removeOption(index)"
                                    class="px-3 py-1.5 text-[#B4552F] hover:bg-[#B4552F]/15 border border-[#B4552F] rounded-lg text-[11px] font-bold cursor-pointer">
                                Hapus
                            </button>
                        </div>
                    </div>
                </template>

                <div x-show="options.length === 0" class="p-6 text-center text-xs text-[#6B5646]">
                    Tidak ada opsi tambahan untuk produk ini.
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('admin.products.index') }}"
               class="px-5 py-2.5 border border-[#CDB48F] text-[#4A3224] text-xs uppercase tracking-wider font-semibold rounded-xl hover:bg-[#E8DCC4] transition">
                Batal
            </a>
            <button type="submit"
                    class="px-7 py-3.5 bg-[#B5753A] text-white text-xs uppercase tracking-wider font-bold rounded-xl shadow-warm hover:bg-[#B4552F] transition cursor-pointer">
                Simpan & Publikasikan Produk
            </button>
        </div>
    </form>
</div>
@endsection
