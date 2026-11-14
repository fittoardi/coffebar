# Design System: Website Coffee Shop — Gaya Rustic

## 1. Filosofi Desain

**Rustic** — seperti kedai kopi di sudut kota tua, dengan dinding bata, meja kayu, dan aroma kopi yang menguar. Desain ini mengutamakan kesan **autentik, hangat, dan berkarakter** lewat tekstur, warna tanah, tipografi vintage, dan ornamen tangan.

**Prinsip utama:**
- **Autentik**: tidak terlalu sempurna, ada kesan buatan tangan (*handcrafted*).
- **Hangat & dalam**: warna gelap dan kaya, bukan warna pucat/pastel.
- **Berkarakter**: tipografi vintage/serif kuat menjadi elemen desain utama.
- **Fungsional**: kesan rustic tidak boleh mengorbankan keterbacaan dan kecepatan.
- **Konsisten**: bahasa visual yang sama dari halaman publik, kasir, hingga admin.

---

## 2. Palet Warna

Skema warna terinspirasi dari **kayu tua, bata merah, kopi hitam, dan tembaga**.

### Warna Utama (Primary)
| Token             | Hex       | Tailwind                   | Penggunaan                          |
|--------------------|-----------|----------------------------|--------------------------------------|
| `rustic-brown`     | `#3E1F0D` | `bg-[#3E1F0D]`             | Navbar, header, tombol utama        |
| `rustic-dark`      | `#2A1208` | `bg-[#2A1208]`             | Footer, overlay gelap               |
| `rustic-medium`    | `#6B3A1F` | `bg-[#6B3A1F]`             | Hover tombol, border aktif          |

### Warna Pendukung (Secondary)
| Token             | Hex       | Tailwind                   | Penggunaan                          |
|--------------------|-----------|----------------------------|--------------------------------------|
| `parchment`        | `#F5E6C8` | `bg-[#F5E6C8]`             | Background utama (seperti kertas tua)|
| `parchment-dark`   | `#EDD9A3` | `bg-[#EDD9A3]`             | Background card, section bergantian |
| `copper`           | `#B87333` | `bg-[#B87333]`             | Aksen utama, border, ornamen        |
| `copper-light`     | `#D4944A` | `bg-[#D4944A]`             | Hover aksen, highlight              |
| `brick`            | `#8B3A2A` | `bg-[#8B3A2A]`             | Dekorasi, aksen merah bata          |
| `wood-light`       | `#C4956A` | `bg-[#C4956A]`             | Divider, ornamen tepi               |

### Warna Fungsional
| Token             | Hex       | Penggunaan                          |
|--------------------|-----------|--------------------------------------|
| `success`          | `#2D6A4F` | Status tersedia, pesanan selesai    |
| `warning`          | `#B87333` | *(reuse copper)* Status diproses    |
| `danger`           `#8B3A2A` | *(reuse brick)* Habis, batal, error |
| `info`             | `#4A6D8C` | Link, hint, notifikasi              |

### Warna Teks
| Token             | Hex       | Penggunaan                               |
|--------------------|-----------|-------------------------------------------|
| `text-dark`        | `#1C0D05` | Heading, judul utama                      |
| `text-body`        | `#3E200E` | Paragraf, deskripsi                       |
| `text-muted`       | `#7A5C45` | Caption, label, placeholder              |
| `text-on-dark`     | `#F5E6C8` | Teks di atas background gelap (parchment)|
| `text-copper`      | `#B87333` | Link, aksen teks                         |

### Tailwind Config — Colors
```js
// tailwind.config.js → theme.extend.colors
colors: {
  rustic: {
    50:  '#FDF6EC',
    100: '#F5E6C8',
    200: '#EDD9A3',
    300: '#D4944A',
    400: '#C4956A',
    500: '#B87333',   // copper
    600: '#8B3A2A',   // brick
    700: '#6B3A1F',
    800: '#3E1F0D',   // rustic-brown
    900: '#2A1208',   // rustic-dark
  },
  success: '#2D6A4F',
  warning: '#B87333',
  danger:  '#8B3A2A',
  info:    '#4A6D8C',
}
```

---

## 3. Tipografi

Rustic mengandalkan **kontras antara serif vintage dan sans-serif bersih** untuk menjaga keterbacaan.

| Elemen          | Font Family               | Weight      | Ukuran (mobile → desktop) | Karakter                     |
|------------------|---------------------------|-------------|---------------------------|-------------------------------|
| Heading utama   | **Playfair Display**      | 800 (Black) | 30px → 48px               | Klasik, kuat, berkarakter     |
| Sub-heading     | **Playfair Display**      | 700 (Bold)  | 20px → 30px               | Elegan, hierarki jelas        |
| Tagline / quote | **Playfair Display**      | 400 Italic  | 16px → 22px               | Puitis, old-world             |
| Navigasi        | **Libre Baskerville**     | 700         | 14px → 15px               | Serif kuat, berkarakter       |
| Body text       | **Lora**                  | 400         | 15px → 16px               | Serif yang sangat mudah dibaca|
| Label / caption | **Lora**                  | 400 Italic  | 12px → 13px               | Halus, pendukung              |
| Harga           | **Playfair Display**      | 700         | 18px → 22px               | Menonjol, mudah dibaca        |
| Tombol          | **Libre Baskerville**     | 700         | 14px → 15px               | Tegas, berkarakter            |
| Area Kasir/Admin| **Lora**                  | 400–700     | 13px → 15px               | Fungsional, tetap on-brand    |

**Import:**
```html
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,800;1,400&family=Libre+Baskerville:wght@400;700&family=Lora:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
```

```js
// tailwind.config.js → theme.extend.fontFamily
fontFamily: {
  display: ['Playfair Display', 'serif'],
  heading: ['Libre Baskerville', 'serif'],
  body:    ['Lora', 'serif'],
}
```

> **Catatan**: Tidak ada font sans-serif di area publik. Area kasir/admin boleh menambahkan `Inter` sebagai fallback untuk data numerik padat.

---

## 4. Tekstur & Ornamen

Ini yang membedakan Rustic dari gaya lain — **tekstur adalah bagian dari desain**.

### Tekstur Background
- **Halaman publik**: background `parchment` (`#F5E6C8`) dengan noise texture halus (SVG filter atau CSS `background-image: url("data:image/svg+xml,...")` untuk efek kertas tua). Opacity tekstur: 15–25%.
- **Section gelap** (hero, footer, navbar): background `rustic-brown` atau `rustic-dark` dengan tekstur kayu atau grain.
- **Card**: background `parchment-dark` dengan border tipis `copper` atau `rustic-700`.

### Ornamen
- **Divider antar section**: bukan garis lurus, melainkan ornamen SVG — sulur daun, bintang kecil, atau pola geometris simpel bergaya vintage. Contoh: `❧ ─────── ✦ ─────── ❧`
- **Border card**: `border-2 border-rustic-500` dengan sudut `rounded-sm` atau `rounded-none` (kotak tegas, bukan bulat modern).
- **Badge dan label**: desain pita/ribbon atau stempel (*stamp*) — bukan pill modern yang melengkung sempurna.
- **Tombol CTA besar**: boleh punya border ganda (`outline`) sebagai efek vintage — tombol + frame di luar tombol.

### Implementasi Tekstur (CSS)
```css
/* Tekstur kertas tua pada body publik */
.texture-parchment {
  background-color: #F5E6C8;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3CfeColorMatrix type='saturate' values='0'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23noise)' opacity='0.08'/%3E%3C/svg%3E");
}

/* Tekstur gelap untuk hero dan footer */
.texture-dark-wood {
  background-color: #3E1F0D;
  background-image: url("data:image/svg+xml,...");
  background-blend-mode: multiply;
}
```

---

## 5. Komponen UI

### 5.1 Navbar

**Konsep**: seperti papan nama kedai kayu gelap, tulisan emas/krem.

```
┌──────────────────────────────────────────────────────────┐
│  ☕ CoffeBar          Beranda  Menu  Berkreasi  │🛒 Masuk│
│  ════════════════════════════════════════════════════════│
│         (background rustic-dark, teks parchment)         │
└──────────────────────────────────────────────────────────┘
```

- **Background**: `bg-rustic-900` (hampir hitam-coklat).
- **Logo**: font Playfair Display Black, warna `copper` (`#B87333`), didampingi ikon cangkir sederhana.
- **Link navigasi**: Libre Baskerville Bold, `text-parchment`, hover → `text-copper` dengan underline `copper`.
- **Garis bawah navbar**: `border-b-2 border-rustic-500` (copper).
- **Active link**: warna `copper`, underline ganda atau dot kecil di bawah teks.
- **Mobile**: hamburger → slide drawer dari kiri. Background drawer `rustic-800`. Tiap link dipisahkan oleh ornamen divider.
- **Ikon keranjang dan login**: warna `parchment`, badge jumlah item dengan warna `copper`.
- Jika sudah login → avatar dengan border `copper`, dropdown bergaya daftar list klasik.

### 5.2 Tombol (Button)

**Konsep**: terasa seperti tombol di mesin kasir tua atau stempel tinta — tegas, bertekstur.

| Varian        | Style                                                                           | Penggunaan                     |
|---------------|----------------------------------------------------------------------------------|--------------------------------|
| **Primary**   | `bg-rustic-800 text-parchment border-2 border-rustic-500 hover:bg-rustic-700 font-heading tracking-wide uppercase` + shadow `shadow-[3px_3px_0px_#B87333]` | CTA utama: Pesan, Checkout    |
| **Secondary** | `bg-transparent text-rustic-800 border-2 border-rustic-700 hover:bg-rustic-100 font-heading tracking-wide uppercase` | Simpan, Filter, Batal         |
| **Copper**    | `bg-copper text-rustic-900 border-2 border-rustic-700 hover:bg-copper-light font-heading tracking-wide uppercase` | Aksi penting: Bayar, Verifikasi|
| **Danger**    | `bg-brick text-parchment border-2 border-rustic-600 hover:opacity-90 font-heading uppercase` | Hapus, Batalkan               |
| **Ghost**     | `text-rustic-500 hover:text-copper underline-offset-4 hover:underline font-body` | Link bergaya tombol            |
| **Disabled**  | `opacity-40 cursor-not-allowed pointer-events-none`                              | Semua varian                  |

- **Shadow efek**: `box-shadow: 3px 3px 0 #B87333` pada Primary dan Copper — memberikan kesan tebal/stamp.
- **Uppercase + letter-spacing**: `uppercase tracking-widest text-sm` — ciri khas desain rustic/vintage.
- Ukuran minimum: `min-h-[48px] min-w-[48px]`.

### 5.3 Card Produk Menu

**Konsep**: seperti kartu menu tulisan tangan di papan kayu.

```
┌─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─┐
  ┌───────────────────────────┐
  │                           │
  │       Foto Produk         │  ← aspect 4:3, sedikit desaturasi (+sepia filter)
  │       (sepia-toned)       │
  └───────────────────────────┘
  ┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄┄  ← divider ornamen tipis
   ☕ KOPI                        ← badge kategori (font heading, uppercase, copper)
   Caramel Latte                  ← nama (Playfair Display Bold)
   Espresso lembut dengan         ← deskripsi (Lora, text-muted, 2 baris)
   karamel dan foam tipis...

   Rp 28.000                      ← harga (Playfair Display Bold, copper)

   [ + TAMBAH KE KERANJANG ]      ← tombol primary full-width
└─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─┘
```
- **Background**: `bg-parchment-dark` / `bg-[#EDD9A3]`
- **Border**: `border-2 border-rustic-500` dengan `rounded-sm` (sudut sedikit membulat, bukan persegi sempurna).
- **Foto**: CSS filter `sepia(30%) contrast(1.05)` untuk kesan vintage konsisten.
- **Hover**: slight `translateY(-4px)` + shadow `shadow-[4px_4px_0px_#B87333]`.
- **Status habis**: overlay diagonal strip merah bata + teks "HABIS" stempel merah.

### 5.4 Badge Kategori

```html
<!-- Bukan pill modern melengkung, tapi label flat tegas -->
<span class="inline-block bg-rustic-800 text-parchment font-heading text-xs uppercase tracking-widest px-3 py-1 border border-rustic-500">
  ☕ KOPI
</span>
```

### 5.5 Badge Status Pesanan

Bergaya **cap stempel** — teks uppercase, border tegas.

| Status     | Style                                                            |
|------------|------------------------------------------------------------------|
| Menunggu   | `border border-rustic-400 text-rustic-500 bg-rustic-100 uppercase font-heading text-xs tracking-widest px-2 py-1` |
| Dibayar    | `border border-info text-info bg-info/10 uppercase ...`          |
| Diterima   | `border border-rustic-500 text-rustic-700 bg-rustic-200 uppercase ...` |
| Diproses   | `border border-warning text-warning bg-warning/10 uppercase ...` |
| Siap       | `border border-success text-success bg-success/10 uppercase ...` |
| Selesai    | `border-2 border-success text-success bg-success/15 uppercase font-bold ...` |
| Batal      | `border border-danger text-danger bg-danger/10 uppercase ...`    |

### 5.6 Form Input

```
Label (Lora Italic, text-muted)
┌─────────────────────────────────────────┐
│  Ketik di sini...                       │   ← border rustic, background parchment
└─────────────────────────────────────────┘
  ⚠ Pesan error (Lora, brick/danger, kecil)
```

- Style: `bg-parchment border-2 border-rustic-400 font-body px-4 py-3 focus:border-copper focus:ring-2 focus:ring-copper/30 rounded-sm`
- Label: `font-body italic text-sm text-rustic-600 mb-1`
- Error: `border-brick text-brick text-sm font-body italic`
- Placeholder: `text-rustic-400 font-body italic`

### 5.7 OTP Input

6 kotak terpisah bergaya kotak kertas — lebih besar, berkarakter:
```
┌────┐ ┌────┐ ┌────┐   ┌────┐ ┌────┐ ┌────┐
│    │ │    │ │    │   │    │ │    │ │    │
└────┘ └────┘ └────┘   └────┘ └────┘ └────┘
```
- Style tiap kotak: `w-14 h-16 text-center text-2xl font-display font-bold bg-parchment border-2 border-rustic-500 rounded-sm focus:border-copper`
- Pemisah tengah: garis ornamen `—` atau spasi lebih lebar.

### 5.8 Modal / Dialog

- Overlay: `bg-rustic-900/70` (gelap hangat, bukan hitam murni)
- Container: `bg-parchment border-2 border-rustic-500 rounded-sm p-6 shadow-[6px_6px_0px_#6B3A1F] max-w-md mx-auto`
- Header modal: garis bawah `border-b-2 border-rustic-200` + judul Playfair Display Bold
- Animasi: fade-in + slide-down dari atas (lebih dramatis dari slide-up)

### 5.9 Toast Notification

- Posisi: `fixed bottom-4 right-4` (desktop), `fixed bottom-4 inset-x-4` (mobile) — di bawah, bukan atas
- Style: `bg-rustic-800 text-parchment border-l-4 border-copper px-4 py-3 font-body shadow-lg rounded-sm`
- Varian: ganti warna `border-l` → success (hijau), danger (brick), info (biru)
- Auto-dismiss 4 detik, animasi `slide-in-right` → `fade-out`

---

## 6. Layout Halaman Publik

### 6.1 Beranda (Landing)
```
┌─────────────────────────────────────────┐
│ NAVBAR (rustic-dark, parchment text)    │
├─────────────────────────────────────────┤
│                                         │
│           HERO SECTION                  │
│  Background: foto kedai + overlay gelap │
│  "Kopi Terbaik,                         │
│   Diracik Untukmu"  ← Playfair 48px    │
│  ─── ✦ ─── ← ornamen divider           │
│  "Sejak 2018, kami menyeduh cerita..."  │
│  ← Playfair Italic, parchment          │
│                                         │
│  [ LIHAT MENU ]   [ BERKREASI ]        │
│  ← tombol primary+secondary bergaya    │
│    vintage (uppercase, shadow copper)   │
│                                         │
├─────────────────────────────────────────┤
│ ❧ ─────────── ✦ ─────────── ❧          │  ← ornamen divider antar section
├─────────────────────────────────────────┤
│                                         │
│  MENU POPULER                           │
│  "Yang Paling Dicintai"  ← sub-heading  │
│  ┌──────┐ ┌──────┐ ┌──────┐ ┌──────┐  │
│  │ Card │ │ Card │ │ Card │ │ Card │  │
│  └──────┘ └──────┘ └──────┘ └──────┘  │
│          [ LIHAT SEMUA MENU → ]         │
│                                         │
├─────────────────────────────────────────┤
│                                         │
│  TENTANG KAMI  (bg rustic-800)          │
│  2 kolom: kiri teks parchment,          │
│  kanan foto dengan border copper        │
│  "Kami bukan sekadar kedai kopi..."     │
│                                         │
├─────────────────────────────────────────┤
│                                         │
│  CARA PESAN (bg parchment)              │
│  3 ikon vintage:                        │
│  ① Pilih   ② Pesan   ③ Nikmati         │
│  Ikon bergaya line-art/stamp            │
│                                         │
├─────────────────────────────────────────┤
│                                         │
│  LOKASI & JAM BUKA (bg parchment-dark)  │
│  Peta embed + kartu info jam            │
│  bergaya label/tiket vintage            │
│                                         │
├─────────────────────────────────────────┤
│                                         │
│  GALERI  (bg rustic-800)                │
│  Grid foto 2x3, sepia-toned             │
│  Hover → warna asli + zoom slight       │
│                                         │
├─────────────────────────────────────────┤
│ FOOTER (rustic-dark)                    │
│ Logo copper, link parchment,            │
│ ornamen divider, © 2026 CoffeBar        │
└─────────────────────────────────────────┘
```

### 6.2 Halaman Menu
```
┌─────────────────────────────────────────┐
│ NAVBAR                                  │
├─────────────────────────────────────────┤
│                                         │
│  "Menu Kami"  ← Playfair 40px, center  │
│  ─── ✦ ─── ← ornamen                   │
│  "Semua dibuat segar, setiap harinya"   │
│  ← Playfair Italic, text-muted          │
│                                         │
│  Filter kategori (tab bergaya tiket):   │
│  ┌────────┐ ┌────────┐ ┌────────┐      │
│  │ SEMUA  │ │  KOPI  │ │NON-KOPI│      │  ← tab aktif: bg-rustic-800 text-parchment
│  └────────┘ └────────┘ └────────┘      │  ← tab nonaktif: bg-parchment border-rustic
│  ┌──────────┐ ┌──────┐                 │
│  │ MAKANAN  │ │ SNACK│                 │
│  └──────────┘ └──────┘                 │
│                                         │
│  Pencarian:                             │
│  ┌────────────────────────────┐         │
│  │ 🔍 Cari menu...            │         │  ← input bergaya rustic
│  └────────────────────────────┘         │
│                                         │
│  ❧ ─────────── ✦ ─────────── ❧         │
│                                         │
│  Grid produk (1→2→3→4 kolom):          │
│  ┌──────┐ ┌──────┐ ┌──────┐            │
│  │ Card │ │ Card │ │ Card │            │
│  └──────┘ └──────┘ └──────┘            │
│  ┌──────┐ ┌──────┐ ┌──────┐            │
│  │ Card │ │ Card │ │ Card │            │
│  └──────┘ └──────┘ └──────┘            │
│                                         │
└─────────────────────────────────────────┘
```

**Detail produk**: slide-over dari kanan (drawer) bergaya lembaran menu restoran lama — foto besar di atas, deskripsi dengan Lora, pilihan ukuran & add-on sebagai checkbox bergaya vintage, lalu tombol "Tambah ke Keranjang".

### 6.3 Custom Menu (Berkreasi)
```
┌─────────────────────────────────────────┐
│ NAVBAR                                  │
├─────────────────────────────────────────┤
│  "Racik Minumanmu"  ← Playfair 36px    │
│  "Jadilah baristamu sendiri"            │
│  ← Playfair Italic, text-muted          │
│                                         │
│  ┌─── PREVIEW ─────┐  ┌─── PILIHAN ──┐ │
│  │                  │  │               │ │
│  │  Ilustrasi gelas │  │ BASE:         │ │
│  │  bergaya woodcut │  │ ○ Espresso   │ │
│  │  (SVG line art)  │  │ ○ Susu       │ │
│  │  berubah sesuai  │  │ ○ Teh Hitam  │ │
│  │  pilihan         │  │               │ │
│  │                  │  │ UKURAN:       │ │
│  │ ───────────      │  │ [S] [M] [L]  │ │  ← tombol pilihan bergaya cap stempel
│  │ Rp 25.000 ✦      │  │               │ │
│  │ (update realtime)│  │ TINGKAT GULA: │ │
│  │                  │  │ ○──●────○    │ │  ← slider bergaya vintage
│  └──────────────────┘  │ 0% 50%  100% │ │
│                        │               │ │
│                        │ SUHU:         │ │
│                        │ [❄ DINGIN]   │ │
│                        │ [☕ PANAS]    │ │
│                        │               │ │
│                        │ TOPPING:      │ │
│                        │ ☑ Boba        │ │  ← checkbox bergaya vintage
│                        │ ☐ Jelly       │ │
│                        │ ☑ Whipped     │ │
│                        └───────────────┘ │
│                                         │
│  ❧ ──── ✦ ──── ❧                       │
│  [ SIMPAN KREASI ]  [ PESAN SEKARANG ] │  ← tombol secondary + primary
│                                         │
└─────────────────────────────────────────┘
```

### 6.4 Keranjang & Checkout
```
┌─────────────────────────────────────────┐
│ NAVBAR                                  │
├─────────────────────────────────────────┤
│  "Keranjang Anda"                       │
│  ─── ✦ ───                              │
│                                         │
│  ┌─────────────────────────────────┐    │
│  │ [foto kecil] Caramel Latte (M)  │    │
│  │              + Boba             │    │
│  │   [-] 2 [+]       Rp 66.000    │    │
│  │                       [🗑 HAPUS]│    │
│  ├─────────────────────────────────┤    │
│  │ [foto kecil] Croissant          │    │
│  │   [-] 1 [+]       Rp 18.000    │    │
│  │                       [🗑 HAPUS]│    │
│  └─────────────────────────────────┘    │
│                                         │
│  ┌─────────────────────────────────┐    │
│  │ Subtotal             Rp 84.000  │    │
│  │ ─────────────────────────────── │    │
│  │ TOTAL                Rp 84.000  │    │  ← Playfair Bold, copper
│  └─────────────────────────────────┘    │
│                                         │
│  METODE PEMBAYARAN:                     │
│  (●) Bayar Online (QRIS / e-Wallet)     │
│  (○) Bayar di Kasir (ambil kode)        │
│                                         │
│  Catatan: [_________________________]   │
│                                         │
│  [ LANJUT KE PEMBAYARAN ]              │
│                                         │
└─────────────────────────────────────────┘
```

### 6.5 Halaman OTP (Verifikasi)

Bergaya **surat/tiket lama** — layout centered, bersih, minimalis tapi tetap rustic.

```
┌─────────────────────────────────────────┐
│                                         │
│         ☕                               │
│         CoffeBar                        │  ← Playfair Black, copper
│                                         │
│  ─────────────────────────────────      │
│                                         │
│    "Masukkan Kode Verifikasi"           │  ← Playfair Bold
│                                         │
│    Kode dikirim ke                      │  ← Lora
│    e***@gmail.com & 0812****56          │
│                                         │
│   ┌──┐ ┌──┐ ┌──┐  ─  ┌──┐ ┌──┐ ┌──┐  │
│   │  │ │  │ │  │     │  │ │  │ │  │  │
│   └──┘ └──┘ └──┘     └──┘ └──┘ └──┘  │
│                                         │
│         [ VERIFIKASI ]                  │  ← tombol primary
│                                         │
│  ─────────────────────────────────      │
│                                         │
│   Belum menerima kode?                  │
│   Kirim ulang (02:45)                   │  ← Lora Italic, copper link
│                                         │
└─────────────────────────────────────────┘
```
- Background: `parchment` + noise texture
- Tidak ada navbar — halaman terfokus.

### 6.6 Halaman Kode Pesanan

Bergaya **tiket konser vintage**.

```
┌─────────────────────────────────────────┐
│                                         │
│    ┌───────────────────────────────┐    │
│    │ ══════════════════════════════│    │
│    │   ☕  CoffeBar                │    │
│    │                               │    │
│    │   Pesanan Berhasil Dibuat!    │    │  ← Playfair Bold
│    │   ─────────────── ✦ ──────── │    │
│    │                               │    │
│    │     ▓▓▓ QR CODE ▓▓▓           │    │
│    │                               │    │
│    │   CF-20417                    │    │  ← Playfair Black, besar
│    │   (ketuk untuk salin)         │    │  ← Lora Italic, kecil
│    │   ─────────────── ✦ ──────── │    │
│    │                               │    │
│    │   Caramel Latte x2  Rp 56.000│    │
│    │   Croissant x1      Rp 18.000│    │
│    │   ─────────────────────────── │    │
│    │   TOTAL             Rp 74.000│    │
│    │                               │    │
│    │ ══════════════════════════════│    │
│    └───────────────────────────────┘    │
│                                         │
│         [ LIHAT PESANAN SAYA ]          │
│                                         │
└─────────────────────────────────────────┘
```

---

## 7. Layout Area Kasir (`/kasir`)

### Konsep Kasir
- **Rustic tetap terjaga** tapi lebih **fungsional** — lebih banyak ruang, kurang dekorasi, font lebih bersih.
- Background: `bg-rustic-50` (parchment sangat muda, tidak terlalu kuning).
- Sidebar: `bg-rustic-800` teks `parchment`, aksen `copper`.
- Font: Lora untuk teks, Playfair Display untuk angka/nilai penting.
- Tidak ada tekstur/noise — cukup warna solid agar data mudah dibaca.

### 7.1 Dashboard Kasir
```
┌──────┬────────────────────────────────────────┐
│      │  Dashboard Kasir          Sabtu, 14 Nov │
│  ☕  │  ═══════════════════════════════════════│
│  D   │  ┌───────────┐ ┌───────────┐ ┌────────┐│
│  a   │  │  MASUK    │ │ DIPROSES  │ │SELESAI ││
│  s   │  │    12     │ │     5     │ │   23   ││
│  h   │  └───────────┘ └───────────┘ └────────┘│
│  b   │  ══════════════════════════════════════ │
│  o   │                                        │
│  a   │  PESANAN MASUK                         │
│  r   │  ┌──────────────────────────────────┐  │
│  d   │  │ 🟡 CF-20417 • ONLINE • DIBAYAR   │  │
│      │  │    2x Caramel Latte, 1x Croissant │  │
│  📦  │  │    Rp 84.000           [TERIMA]  │  │
│  P   │  ├──────────────────────────────────┤  │
│  e   │  │ 🟠 CF-20418 • KASIR • MENUNGGU   │  │
│  s   │  │    1x Americano, 1x Roti Bakar   │  │
│  a   │  │    Rp 37.000      [PROSES BAYAR] │  │
│  n   │  └──────────────────────────────────┘  │
│  a   │                                        │
│  n   │  ══════════════════════════════════════ │
│      │  [ + PESANAN WALK-IN BARU ]            │
│  📊  │                                        │
│  🕐  │                                        │
└──────┴────────────────────────────────────────┘
```

- Kartu statistik: `bg-parchment border-2 border-rustic-300` dengan angka Playfair Display Bold.
- Baris pesanan: highlight flash `bg-copper/20` saat pesanan baru masuk (animasi 1 detik).
- Tombol aksi kasir: tombol Copper style (tegas, uppercase).

### 7.2 Struk Digital

Bergaya **struk mesin kasir lama**, font monospace dengan ornamen ASCII.

```
╔═════════════════════════════╗
║          ☕ CoffeBar         ║
║      Jl. Kopi No. 12        ║
║   Telp: 021-1234567         ║
╠═════════════════════════════╣
║  No: CF-20417               ║
║  Tgl: 14/11/2026 10:30      ║
║  Kasir: Budi S.             ║
╠═════════════════════════════╣
║  Caramel Latte M   x2       ║
║    + Boba                   ║
║                     66.000  ║
║  Croissant          x1      ║
║                     18.000  ║
╠═════════════════════════════╣
║  TOTAL          Rp  84.000  ║
║  Tunai          Rp 100.000  ║
║  Kembali        Rp  16.000  ║
╠═════════════════════════════╣
║   Terima kasih telah        ║
║   berkunjung! ☕             ║
║   www.coffebar.id           ║
╚═════════════════════════════╝
```
- Font: `font-mono` (Courier New / monospace system).
- Tombol di bawah struk: `[ 🖨 CETAK STRUK ]` dan `[ SELESAI ]`.

---

## 8. Layout Panel Admin (`/admin`)

- **Sidebar** lebih luas: Dashboard, Menu, Kategori, Konten, Karyawan, Laporan, Pengaturan.
- Sidebar: `bg-rustic-900`, aksen copper, font Libre Baskerville.
- Header tiap halaman: Playfair Display Bold + ornamen divider.
- **Halaman laporan**: kartu ringkasan (bg parchment) + grafik garis (warna copper & rustic) + tabel detail + tombol `[ EKSPOR CSV ]`.
- **CRUD Menu**: tabel bergaya rustic — header `bg-rustic-800 text-parchment`, baris bergantian `bg-parchment` / `bg-parchment-dark`.
- **Manajemen karyawan**: badge role dengan gaya stempel — `ADMIN`, `KASIR`.

---

## 9. Ikonografi

Menggunakan **Heroicons** (outline style) — tetap konsisten, tidak perlu icon font custom. Namun dipadukan dengan beberapa **SVG ornamen inline** untuk elemen dekoratif.

| Konteks           | Ikon Heroicon                | Catatan                            |
|--------------------|-------------------------------|-------------------------------------|
| Keranjang          | `shopping-bag`               |                                     |
| Menu               | `squares-2x2`                |                                     |
| Berkreasi          | `beaker`                     |                                     |
| Pesanan saya       | `clipboard-document-list`    |                                     |
| Kasir              | `banknotes`                  |                                     |
| Admin              | `cog-6-tooth`                |                                     |
| Lokasi             | `map-pin`                    |                                     |
| Jam buka           | `clock`                      |                                     |
| Telepon            | `phone`                      |                                     |
| User / Profil      | `user-circle`                |                                     |
| Logout             | `arrow-right-on-rectangle`   |                                     |
| Hapus              | `trash`                      | Warna brick/danger                 |
| Edit               | `pencil-square`              |                                     |
| Cari               | `magnifying-glass`           |                                     |
| QR Code            | `qr-code`                    |                                     |
| Sukses             | `check-circle`               | Warna success                      |
| Status proses      | `arrow-path`                 | Spinning saat loading              |
| Habis (menu)       | `x-circle`                   | Warna danger/brick                 |
| Shift kasir        | `calendar-days`              |                                     |
| Laporan            | `chart-bar`                  |                                     |
| Ekspor CSV         | `arrow-down-tray`            |                                     |

**Ornamen dekoratif** (SVG inline atau CSS content):
- Divider antar section: `❧ ─────── ✦ ─────── ❧`
- Bullet point daftar: `✦` atau `•` copper
- Dekorasi heading: garis bawah `border-b-2 border-copper` dengan lebar terbatas

---

## 10. Foto & Ilustrasi

- **Hero**: foto kedai kopi suasana rustic (meja kayu, bata, lampu pijar). Overlay: `bg-gradient-to-b from-rustic-900/70 to-rustic-900/20`.
- **Produk**: foto dengan background natural/kayu. CSS filter: `sepia(25%) contrast(1.1) brightness(0.95)` agar konsisten.
- **Ilustrasi Custom Menu**: SVG line-art bergaya woodcut/ukiran kayu — gelas, biji kopi, daun, dll. Warna monokrom copper.
- **Placeholder**: ilustrasi cangkir kopi line-art minimalis, warna `copper` di atas background `parchment-dark`.
- **Galeri**: grid 2x3, foto sepia. Hover → warna asli + `scale-105`.

---

## 11. Animasi & Transisi

**Prinsip**: animasi rustic terasa sedikit lebih lambat dan organik dari modern minimalism — tidak terlalu snappy, lebih *earthy*.

| Elemen                      | Animasi                                                    |
|-----------------------------|------------------------------------------------------------|
| Card hover                  | `translateY(-6px)` + shadow copper, `duration-300`         |
| Modal masuk                 | `fade-in` + `slide-down` dari atas, 250ms                  |
| Toast notifikasi            | `slide-in-right` → auto-dismiss `fade-out`, 350ms          |
| Harga (custom menu)         | Angka fade-change, `duration-200`                          |
| Tambah ke keranjang         | Badge keranjang "bounce" + warna flash copper singkat      |
| Pesanan baru (kasir)        | Baris `bg-copper/20` fade-in, fade keluar setelah 1.5 dtk  |
| Foto galeri hover           | `filter sepia(0)` + `scale-105`, `duration-400`            |
| Loading                     | Skeleton shimmer dengan warna `parchment-dark` → `parchment`|
| Tab/filter menu             | `border-bottom` slide dengan `duration-200`                |
| Tombol klik                 | `scale-95` momentary, `duration-100`                       |

**Aturan**: tidak ada animasi melebihi 400ms. Jangan gunakan efek yang terasa "tech" (blur neon, glow warna terang, dsb.).

---

## 12. Responsive Breakpoints

Mengikuti default Tailwind:

| Breakpoint | Ukuran    | Target                    |
|------------|-----------|---------------------------|
| Default    | < 640px   | Smartphone portrait       |
| `sm`       | ≥ 640px   | Smartphone landscape      |
| `md`       | ≥ 768px   | Tablet portrait           |
| `lg`       | ≥ 1024px  | Tablet landscape / laptop |
| `xl`       | ≥ 1280px  | Desktop                   |

**Perubahan utama per breakpoint:**
- **Mobile**: single column, hamburger → drawer kiri (rustic-800), bottom sheet untuk detail produk, stacked custom menu (preview atas, pilihan bawah).
- **Tablet**: 2 kolom grid produk, sidebar kasir collapse (ikon + tooltip).
- **Desktop**: 3–4 kolom grid produk, sidebar kasir/admin terbuka penuh.
- **Font scaling**: `text-3xl md:text-4xl lg:text-5xl` untuk heading hero.

---

## 13. Aksesibilitas

Gaya rustic tidak boleh mengorbankan aksesibilitas.

- Semua gambar wajib punya `alt` teks deskriptif.
- Kontras warna minimum rasio **4.5:1** untuk teks normal, **3:1** untuk teks besar — terutama warna `copper` pada `parchment` wajib diverifikasi.
- Focus ring: `focus:ring-2 focus:ring-copper/60 focus:ring-offset-2 focus:ring-offset-parchment` pada semua elemen interaktif.
- Form: setiap input punya `<label>` terhubung, pesan error terhubung via `aria-describedby`.
- Navigasi keyboard: tab order logis, skip-to-content link tersembunyi di paling atas.
- Tombol/link: minimal area sentuh 44×44px.
- Jangan gunakan `sepia` filter pada gambar yang berisi teks penting — cukup pada foto dekoratif.

---

## 14. Referensi Konfigurasi Tailwind Lengkap

```js
// tailwind.config.js
import defaultTheme from 'tailwindcss/defaultTheme';

export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/**/*.blade.php',
    './resources/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        rustic: {
          50:  '#FDF6EC',
          100: '#F5E6C8',   // parchment
          200: '#EDD9A3',   // parchment-dark
          300: '#D4944A',   // copper-light
          400: '#C4956A',   // wood-light
          500: '#B87333',   // copper (aksen utama)
          600: '#8B3A2A',   // brick
          700: '#6B3A1F',   // rustic-medium
          800: '#3E1F0D',   // rustic-brown
          900: '#2A1208',   // rustic-dark
        },
        success: '#2D6A4F',
        warning: '#B87333',
        danger:  '#8B3A2A',
        info:    '#4A6D8C',
      },
      fontFamily: {
        display: ['Playfair Display', 'Georgia', 'serif'],
        heading: ['Libre Baskerville', 'Georgia', 'serif'],
        body:    ['Lora', 'Georgia', 'serif'],
        mono:    ['Courier New', 'Courier', 'monospace'],
      },
      backgroundImage: {
        'texture-parchment': "url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3CfeColorMatrix type='saturate' values='0'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)' opacity='0.07'/%3E%3C/svg%3E\")",
      },
      boxShadow: {
        'rustic':    '3px 3px 0px #B87333',
        'rustic-lg': '6px 6px 0px #6B3A1F',
        'rustic-sm': '2px 2px 0px #B87333',
      },
      borderRadius: {
        'sm': '2px',   // lebih kotak dari default, khas rustic
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
};
```

---

## 15. Panduan Penggunaan Cepat

### Halaman Publik
```html
<!-- Background parchment bertekstur -->
<body class="bg-rustic-100 bg-texture-parchment font-body text-rustic-800">

<!-- Heading -->
<h1 class="font-display font-black text-5xl text-rustic-900">Judul</h1>
<p class="font-body italic text-rustic-500">Tagline elegan di sini</p>

<!-- Divider ornamen -->
<div class="text-center text-rustic-500 my-8 tracking-widest">❧ ─────── ✦ ─────── ❧</div>

<!-- Tombol primary -->
<button class="bg-rustic-800 text-rustic-100 border-2 border-rustic-500 font-heading uppercase tracking-widest text-sm px-6 py-3 shadow-rustic hover:bg-rustic-700 transition-all duration-200">
  PESAN SEKARANG
</button>

<!-- Card produk -->
<div class="bg-rustic-200 border-2 border-rustic-500 rounded-sm shadow-rustic hover:-translate-y-1 hover:shadow-rustic-lg transition-all duration-300">
  ...
</div>
```

### Area Kasir / Admin
```html
<!-- Sidebar -->
<aside class="bg-rustic-900 text-rustic-100 font-heading ...">
  <a class="hover:text-rustic-500 uppercase tracking-wide text-sm ...">Dashboard</a>
</aside>

<!-- Konten utama -->
<main class="bg-rustic-50 font-body ...">
  <h2 class="font-display font-bold text-2xl text-rustic-800 border-b-2 border-rustic-500 pb-2">
    Pesanan Masuk
  </h2>
</main>
```
