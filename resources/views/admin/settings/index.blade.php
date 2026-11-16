@extends('layouts.admin')

@section('title', 'Pengaturan Profil Kedai')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="border-b-2 border-copper/30 pb-4">
        <span class="text-xs uppercase font-heading tracking-widest text-copper block">Konten & Informasi Kedai</span>
        <h1 class="text-3xl font-display font-black text-rustic-900 tracking-tight">Pengaturan Profil Kedai</h1>
        <p class="text-xs font-heading text-rustic-600 mt-1">Kelola narasi, jam buka, kontak, dan konten yang tampil di halaman beranda publik.</p>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- SECTION: INFORMASI DASAR -->
        <div class="bg-[#FFFDF9] border-2 border-rustic-300 p-6 shadow-rustic-sm space-y-4">
            <h2 class="font-display font-bold text-lg text-rustic-900 border-b border-rustic-200 pb-2">
                ☕ 1. Informasi Umum Brand
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-heading uppercase tracking-wider text-rustic-700 font-bold mb-1">
                        Nama Kedai
                    </label>
                    <input type="text" name="shop_name" value="{{ old('shop_name', $settings['shop_name'] ?? 'CoffeBar') }}"
                           class="w-full bg-parchment-light border-2 border-rustic-300 p-2.5 text-sm font-body text-rustic-900 focus:border-copper focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-heading uppercase tracking-wider text-rustic-700 font-bold mb-1">
                        Slogan / Tagline
                    </label>
                    <input type="text" name="shop_tagline" value="{{ old('shop_tagline', $settings['shop_tagline'] ?? 'Kedai Kopi Autentik Nusantara') }}"
                           class="w-full bg-parchment-light border-2 border-rustic-300 p-2.5 text-sm font-body text-rustic-900 focus:border-copper focus:outline-none">
                </div>
            </div>
        </div>

        <!-- SECTION: HERO BERANDA -->
        <div class="bg-[#FFFDF9] border-2 border-rustic-300 p-6 shadow-rustic-sm space-y-4">
            <h2 class="font-display font-bold text-lg text-rustic-900 border-b border-rustic-200 pb-2">
                ✨ 2. Tampilan Hero Beranda
            </h2>

            <div>
                <label class="block text-xs font-heading uppercase tracking-wider text-rustic-700 font-bold mb-1">
                    Judul Utama Hero
                </label>
                <input type="text" name="hero_title" value="{{ old('hero_title', $settings['hero_title'] ?? 'Kopi Terbaik, Diracik Untukmu') }}"
                       class="w-full bg-parchment-light border-2 border-rustic-300 p-2.5 text-sm font-body text-rustic-900 focus:border-copper focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-heading uppercase tracking-wider text-rustic-700 font-bold mb-1">
                    Kutipan / Subtitle Hero
                </label>
                <textarea name="hero_subtitle" rows="2"
                          class="w-full bg-parchment-light border-2 border-rustic-300 p-2.5 text-sm font-body text-rustic-900 focus:border-copper focus:outline-none">{{ old('hero_subtitle', $settings['hero_subtitle'] ?? 'Setiap tetes seduhan menyimpan cerita kehangatan meja kayu, obrolan akrab, dan biji kopi pilihan yang disangrai dengan penuh cinta.') }}</textarea>
            </div>
        </div>

        <!-- SECTION: TENTANG KAMI -->
        <div class="bg-[#FFFDF9] border-2 border-rustic-300 p-6 shadow-rustic-sm space-y-4">
            <h2 class="font-display font-bold text-lg text-rustic-900 border-b border-rustic-200 pb-2">
                📜 3. Cerita Kedai (Tentang Kami)
            </h2>

            <div>
                <label class="block text-xs font-heading uppercase tracking-wider text-rustic-700 font-bold mb-1">
                    Judul Cerita
                </label>
                <input type="text" name="about_title" value="{{ old('about_title', $settings['about_title'] ?? 'Bukan Sekadar Kedai Kopi, Tapi Rumah Cerita') }}"
                       class="w-full bg-parchment-light border-2 border-rustic-300 p-2.5 text-sm font-body text-rustic-900 focus:border-copper focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-heading uppercase tracking-wider text-rustic-700 font-bold mb-1">
                    Paragraf Pertama
                </label>
                <textarea name="about_description_1" rows="3"
                          class="w-full bg-parchment-light border-2 border-rustic-300 p-2.5 text-sm font-body text-rustic-900 focus:border-copper focus:outline-none">{{ old('about_description_1', $settings['about_description_1'] ?? 'Lahir dari kecintaan pada aroma biji kopi yang baru digiling dan hangatnya interaksi manusia. Di CoffeBar, kami percaya bahwa secangkir kopi terbaik bukan hanya tentang teknik ekstraksi, melainkan tentang momen hening yang Anda nikmati atau obrolan mendalam bersama sahabat.') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-heading uppercase tracking-wider text-rustic-700 font-bold mb-1">
                    Paragraf Kedua
                </label>
                <textarea name="about_description_2" rows="3"
                          class="w-full bg-parchment-light border-2 border-rustic-300 p-2.5 text-sm font-body text-rustic-900 focus:border-copper focus:outline-none">{{ old('about_description_2', $settings['about_description_2'] ?? 'Kami bekerja sama langsung dengan para petani kopi lokal dari Gayo, Kintamani, Toraja, hingga Mandailing untuk menyajikan cita rasa asli yang terjaga kesegarannya.') }}</textarea>
            </div>
        </div>

        <!-- SECTION: OPERASIONAL & LOKASI -->
        <div class="bg-[#FFFDF9] border-2 border-rustic-300 p-6 shadow-rustic-sm space-y-4">
            <h2 class="font-display font-bold text-lg text-rustic-900 border-b border-rustic-200 pb-2">
                📍 4. Lokasi & Operasional
            </h2>

            <div>
                <label class="block text-xs font-heading uppercase tracking-wider text-rustic-700 font-bold mb-1">
                    Alamat Lengkap Kedai
                </label>
                <input type="text" name="address" value="{{ old('address', $settings['address'] ?? 'Jl. Kopi No. 12, Senopati, Kebayoran Baru, Jakarta Selatan') }}"
                       class="w-full bg-parchment-light border-2 border-rustic-300 p-2.5 text-sm font-body text-rustic-900 focus:border-copper focus:outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-heading uppercase tracking-wider text-rustic-700 font-bold mb-1">
                        Jam Operasional
                    </label>
                    <input type="text" name="opening_hours" value="{{ old('opening_hours', $settings['opening_hours'] ?? 'Setiap hari 08.00 – 22.00 WIB') }}"
                           class="w-full bg-parchment-light border-2 border-rustic-300 p-2.5 text-sm font-body text-rustic-900 focus:border-copper focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-heading uppercase tracking-wider text-rustic-700 font-bold mb-1">
                        Nomor Kontak / WhatsApp
                    </label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone'] ?? '0812-3456-7890') }}"
                           class="w-full bg-parchment-light border-2 border-rustic-300 p-2.5 text-sm font-mono text-rustic-900 focus:border-copper focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-heading uppercase tracking-wider text-rustic-700 font-bold mb-1">
                    Tautan Google Maps (URL)
                </label>
                <input type="url" name="maps_url" value="{{ old('maps_url', $settings['maps_url'] ?? 'https://maps.google.com') }}"
                       class="w-full bg-parchment-light border-2 border-rustic-300 p-2.5 text-sm font-body text-rustic-900 focus:border-copper focus:outline-none">
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end space-x-3">
            <button type="submit"
                    class="px-6 py-3 bg-copper text-parchment font-heading text-xs uppercase tracking-wider font-bold shadow-rustic-sm hover:bg-copper-dark transition">
                Simpan Perubahan Profil Kedai
            </button>
        </div>
    </form>
</div>
@endsection
