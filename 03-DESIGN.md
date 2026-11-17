# Design System: CoffeBar — Retro & Vintage Web Design Style

> **Referensi Desain**: Behance *Retro and Vintage Style in Web Design*  
> **Karakter Utama**: Nostalgia 1970s, Tangible Tactile Press, Warm Roastery Earth Tones, Stamp Seals, Bold Retro Typography & Diner Ticket Elements.

---

## 1. Filosofi & Arah Estetika Desain

Arah visual CoffeBar bertransformasi dari sekadar rustic biasa menjadi **Retro & Vintage Web Design** yang autentik, berani, dan kaya karakter—menggabungkan kehangatan kedai kopi klasik era 70-an dengan kemudahan interaksi modern (*Old Aesthetic, Modern Performance*).

### Prinsip Utama:
1. **Warm Nostalgia (Nostalgia Hangat)**:
   Dominasi warna krem susu (*warm vanilla cream*), terakota jingga bakar (*burnt orange / terracotta 70s*), mustard hangat (*golden ochre*), dan cokelat tua pekat (*vintage espresso ink*).
2. **Tactile & Tangible (Terasa Nyata & Dapat Disentuh)**:
   Komponen UI menggunakan bayangan tegas tanpa blur (*hard offset drop-shadow 4px/6px*), border tinta hitam 2px, dan efek tekan fisik (*active:translate-x-1 active:translate-y-1 active:shadow-none*) menyerupai tombol mesin kasir atau papan menu vintage.
3. **Typography with Personality (Tipografi Berkarakter Kuat)**:
   Kontras antara *Playfair Display* yang tebal dan anggun, *Libre Baskerville* all-caps dengan letter-spacing lebar, serta ornamen vintage khas percetakan retro (`★`, `✦`, `✿`, `✪`, `❧`).
4. **Authentic Graphical Badges (Stempel, Stiker & Tiket)**:
   - Stempel segel melingkar yang berputar perlahan (*Rotating Roastery Seal Stamp: "★ COFFEBAR ROASTERY ★ ESTD 2026 ★"*).
   - Stiker miring (*tilted sticker badges: -rotate-2 / rotate-2*) untuk label "BEST SELLER", "FAVORIT", dan "SPECIALTY".
   - Kupon/tiket dengan garis putus-putus perforasi (*ticket stub & dashed perforation*).
5. **Zero-Gravity Floating Beans**:
   Biji kopi sangrai meletup transparan yang melayang di sisi-sisi section memberikan kedalaman dimensi 3D yang dinamis.

---

## 2. Palet Warna (Color Palette)

Skema warna diadaptasi dari palet era 1970-an roastery klasik:

### Warna Inti (Core Palette)
| Token Visual | Hex Code | Deskripsi & Penggunaan |
|---|---|---|
| `vintage-cream` (`parchment`) | `#FBF4E6` | Latar belakang utama seluruh halaman (kertas vanila tua lembut) |
| `vintage-paper` (`parchment-dark`) | `#F3E5CF` | Latar kartu menu, tiket, dan kontainer sekunder |
| `retro-terracotta` (`copper`) | `#CF5328` | Aksen retro utama (tombol CTA, badge aktif, border highlight) |
| `retro-mustard` (`copper-light`)| `#E2A63B` | Aksen kuning mustard klasik (stiker promo, bintang rating, status) |
| `retro-sage` | `#4E6E58` | Hijau zaitun retro untuk status tersedia, organik, dan sukses |
| `retro-burgundy` (`brick`) | `#A83823` | Merah bata tua klasik untuk peringatan, promo terbatas, dan batal |
| `vintage-roast` | `#5C2B14` | Cokelat kayu sangrai untuk hover border dan aksen kedua |
| `dark-espresso` | `#2E150A` | Latar belakang hero section dan navbar |
| `vintage-ink` | `#1B0F07` | Warna teks utama, heading, border 2px, dan solid hard shadow |

### Token Tailwind (app.css)
```css
@theme {
    --color-rustic-50: #FFFDF8;
    --color-rustic-100: #FBF4E6;
    --color-rustic-200: #F3E5CF;
    --color-rustic-300: #E2A63B; /* Retro Mustard */
    --color-rustic-400: #D87D38; /* Warm Amber */
    --color-rustic-500: #CF5328; /* Retro Terracotta */
    --color-rustic-600: #A83823; /* Retro Burgundy */
    --color-rustic-700: #5C2B14; /* Deep Roast */
    --color-rustic-800: #2E150A; /* Dark Vintage Espresso */
    --color-rustic-900: #1B0F07; /* Vintage Ink Black */

    --color-retro-orange: #CF5328;
    --color-retro-mustard: #E2A63B;
    --color-retro-sage: #4E6E58;

    --font-display: 'Playfair Display', Georgia, serif;
    --font-heading: 'Libre Baskerville', Georgia, serif;
    --font-body: 'Lora', Georgia, serif;

    --shadow-rustic: 4px 4px 0px #1B0F07;
    --shadow-rustic-lg: 6px 6px 0px #1B0F07;
    --shadow-rustic-sm: 2px 2px 0px #1B0F07;
    --shadow-retro-orange: 4px 4px 0px #CF5328;
    --shadow-retro-mustard: 4px 4px 0px #E2A63B;
}
```

---

## 3. Tipografi Retro

| Elemen UI | Font Family | Weight & Style | Karakter & Penggunaan |
|---|---|---|---|
| **Display Title (Hero)** | Playfair Display | 900 (Black) Italic/Normal | Judul panggung utama, headline dramatis |
| **Section Heading** | Playfair Display | 800 (Bold) | Judul "Menu Paling Dicintai", "Tentang Kami" |
| **Navigasi & Subhead** | Libre Baskerville | 700 (Bold) Uppercase | Menu navigasi, label kategori, sub-judul |
| **Badges & Stempel** | Libre Baskerville | 700 Uppercase Wide Tracking | "ESTD 2026", "SPECIAL SELECTION", "GRADE 1" |
| **Body & Deskripsi** | Lora | 400 Regular / Italic | Paragraf cerita, deskripsi minuman |
| **Harga & Angka** | Playfair Display | 800 Bold | Label harga produk dengan kontras tinggi |

---

## 4. Komponen Khas Retro & Vintage

### 1. Stempel Segel Melingkar (*Rotating Roastery Stamp*)
Segel vintage dengan tulisan melingkar `★ COFFEBAR ROASTERY ★ ESTD 2026 ★ 100% ARTISAN COFFEE ★` yang berputar perlahan dengan animasi CSS `@keyframes spin-slow` (22 detik per putaran).

### 2. Kartu Menu dengan Stiker Miring (*Tilted Sticker Badges*)
Setiap kartu menu memiliki bingkai tinta 2px solid (`border-2 border-rustic-900`), latar vanila (`bg-parchment-dark`), dan bayangan offset retro 4px (`shadow-rustic`). Di pojok kartu terdapat stiker berputar `-rotate-2` berwarna kuning mustard (`bg-retro-mustard`) bertuliskan `★ FAVORIT` atau `★ BEST SELLER`.

### 3. Tombol Taktil Fisik (*Tactile Press Buttons*)
Tombol memiliki border tebal 2px, warna oranye terakota atau vanila, serta hard shadow:
```html
<button class="bg-copper text-rustic-900 border-2 border-rustic-900 font-heading font-bold uppercase py-3 px-6 shadow-rustic transition-all duration-150 active:translate-x-1 active:translate-y-1 active:shadow-none">
    PESAN SEKARANG →
</button>
```

### 4. Perforasi Kupon & Struk Kasir (*Ticket Stub & Receipt Slip*)
Struk pesanan di halaman Checkout dan Detail Pesanan dirancang menyerupai tiket pesanan kedai klasik era 70-an dengan garis potong bergerigi (*dashed perforation*), watermark stempel kasir, dan nomor tiket bergaya retro.

### 5. Tekstur Halftone & Grid Retro
Latar belakang dihiasi dengan tekstur bintik cetak (*halftone dots pattern*) dan butiran halus kertas tua (*vintage paper noise*) untuk menghilangkan kesan datar digital dan memberikan sentuhan analog yang kaya.

---

## 5. Implementasi & Standardisasi Halaman

| Halaman | Fitur Utama Retro |
|---|---|
| **Beranda (`home.blade.php`)** | Hero espresso hitam pekat, video sinematik meletupnya biji kopi, stempel putar emas, tab pemilih biji nusantara (Gayo, Kintamani, Toraja), kartu menu dengan stiker miring, langkah pesanan prangko tiket, dan biji melayang transparan di sisi section. |
| **Katalog Menu (`menu/index.blade.php`)** | Papan menu bergaya diner klasik, tab filter bergaris ganda, kartu produk taktil retro dengan modal kustomisasi lengkap. |
| **Racik Minuman (`custom/index.blade.php`)** | *Coffee Laboratory Board* dengan kontrol racikan taktil dan slip resep cetak instan. |
| **Keranjang & Checkout (`cart/index.blade.php`, `checkout/index.blade.php`)** | Buku bon pesanan retro dengan rincian harga bergaris putus-putus dan tombol verifikasi instan. |
| **Struk Pesanan (`orders/receipt.blade.php`)** | Tiket pesanan berlubang (*perforated ticket*) dengan QR code, cap stempel kasir, dan garis tanda tangan barista. |
| **Portal Login (`auth/*.blade.php`)** | Kartu pas masuk anggota vintage (*Membership Pass Slip*). |
| **Panel Kasir & Admin (`cashier/*`, `admin/*`)** | Dashboard operasional dengan aksen tembaga retro, kontras tajam, dan status visual yang mudah dibaca barista di balik meja racik. |
