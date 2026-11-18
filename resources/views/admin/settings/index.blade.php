@extends('layouts.admin')

@section('title', 'Pengaturan Profil Kedai')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header (03-DESIGN.md Section 19) -->
    <div class="border-b border-dashed border-[#CDB48F] pb-4">
        <span class="text-xs uppercase tracking-widest text-[#B5753A] font-bold block">Konten & Informasi Kedai</span>
        <h1 class="text-3xl font-display font-semibold text-[#2B1D14] tracking-tight mt-1">Pengaturan Profil Kedai</h1>
        <p class="text-xs text-[#6B5646] mt-1">Kelola narasi, jam buka, kontak, dan konten yang tampil di halaman beranda publik.</p>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- SECTION: INFORMASI DASAR -->
        <div class="bg-[#FFFDF8] border border-[#CDB48F] p-6 rounded-2xl shadow-sm space-y-4">
            <h2 class="font-display font-semibold text-lg text-[#2B1D14] border-b border-dashed border-[#CDB48F] pb-3">
                ☕ 1. Informasi Umum Brand
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                        Nama Kedai
                    </label>
                    <input type="text" name="shop_name" value="{{ old('shop_name', $settings['shop_name'] ?? 'CoffeBar') }}"
                           class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                        Slogan / Tagline
                    </label>
                    <input type="text" name="shop_tagline" value="{{ old('shop_tagline', $settings['shop_tagline'] ?? 'Kedai Kopi Autentik Nusantara') }}"
                           class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">
                </div>
            </div>
        </div>

        <!-- SECTION: HERO BERANDA -->
        <div class="bg-[#FFFDF8] border border-[#CDB48F] p-6 rounded-2xl shadow-sm space-y-4">
            <h2 class="font-display font-semibold text-lg text-[#2B1D14] border-b border-dashed border-[#CDB48F] pb-3">
                ✨ 2. Tampilan Hero Beranda
            </h2>

            <div>
                <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                    Judul Utama Hero
                </label>
                <input type="text" name="hero_title" value="{{ old('hero_title', $settings['hero_title'] ?? 'Kopi Terbaik, Diracik Untukmu') }}"
                       class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">
            </div>

            <div>
                <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                    Kutipan / Subtitle Hero
                </label>
                <textarea name="hero_subtitle" rows="2"
                          class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">{{ old('hero_subtitle', $settings['hero_subtitle'] ?? 'Cita Rasa Juara Dari Bumi Nusantara') }}</textarea>
            </div>
        </div>

        <!-- SECTION: TENTANG KAMI -->
        <div class="bg-[#FFFDF8] border border-[#CDB48F] p-6 rounded-2xl shadow-sm space-y-4">
            <h2 class="font-display font-semibold text-lg text-[#2B1D14] border-b border-dashed border-[#CDB48F] pb-3">
                📜 3. Cerita Kedai (Tentang Kami)
            </h2>

            <div>
                <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                    Judul Cerita
                </label>
                <input type="text" name="about_title" value="{{ old('about_title', $settings['about_title'] ?? 'Bukan Sekadar Kedai Kopi, Tapi Rumah Cerita') }}"
                       class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">
            </div>

            <div>
                <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                    Paragraf Pertama
                </label>
                <textarea name="about_description_1" rows="3"
                          class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">{{ old('about_description_1', $settings['about_description_1'] ?? 'Lahir dari kecintaan pada aroma biji kopi yang baru digiling dan hangatnya interaksi manusia.') }}</textarea>
            </div>

            <div>
                <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                    Paragraf Kedua
                </label>
                <textarea name="about_description_2" rows="3"
                          class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">{{ old('about_description_2', $settings['about_description_2'] ?? 'Kami bekerja sama langsung dengan para petani kopi lokal dari Gayo, Kintamani, Toraja, hingga Mandailing.') }}</textarea>
            </div>
        </div>

        <!-- SECTION: OPERASIONAL & LOKASI -->
        <div class="bg-[#FFFDF8] border border-[#CDB48F] p-6 rounded-2xl shadow-sm space-y-4">
            <h2 class="font-display font-semibold text-lg text-[#2B1D14] border-b border-dashed border-[#CDB48F] pb-3">
                📍 4. Lokasi & Operasional
            </h2>

            <div>
                <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                    Alamat Lengkap Kedai
                </label>
                <input type="text" name="address" value="{{ old('address', $settings['address'] ?? 'Jl. Senopati No. 88, Jakarta Selatan') }}"
                       class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                        Jam Operasional
                    </label>
                    <input type="text" name="opening_hours" value="{{ old('opening_hours', $settings['opening_hours'] ?? 'Setiap Hari: 08:00 - 22:00 WIB') }}"
                           class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                        Nomor Kontak / WhatsApp
                    </label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone'] ?? '0812-3456-7890') }}"
                           class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm font-mono text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs uppercase tracking-wider text-[#4A3224] font-bold mb-1.5">
                    Tautan Google Maps (URL)
                </label>
                <input type="url" name="maps_url" value="{{ old('maps_url', $settings['maps_url'] ?? 'https://maps.google.com') }}"
                       class="w-full bg-[#FFFDF8] border border-[#CDB48F] p-3 text-sm text-[#2B1D14] rounded-xl focus:border-[#B5753A] focus:outline-none">
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end space-x-3">
            <button type="submit"
                    class="px-8 py-3.5 bg-[#B5753A] text-white text-xs uppercase tracking-wider font-bold rounded-xl shadow-warm hover:bg-[#B4552F] transition cursor-pointer">
                Simpan Perubahan Profil Kedai
            </button>
        </div>
    </form>
</div>
@endsection
