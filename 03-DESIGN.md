# ☕ DESIGN.md — Web Coffeeshop · Rustic Modern

> **Konsep:** Kehangatan kedai kopi tradisional (kayu, kertas kraft, biji kopi sangrai) bertemu tata letak modern yang bersih, lega, dan mudah dipakai. Terasa *hangat, jujur, dan handcrafted*, tapi tetap rapi dan cepat.

**Kata kunci:** hangat · organik · bertekstur · lega · artisan · tenang

---

## 1. Prinsip Desain

1. **Hangat, bukan ramai.** Warna tanah dan krem sebagai dasar. Aksen dipakai hemat.
2. **Tekstur halus, bukan berat.** Grain kertas dan serat kayu hanya sebagai sentuhan, jangan sampai mengganggu keterbacaan.
3. **Tipografi sebagai karakter.** Serif ekspresif untuk judul, sans-serif bersih untuk isi.
4. **Ruang napas.** Whitespace yang lega adalah unsur "modern" dari gaya ini.
5. **Foto jujur.** Pakai foto nyata dengan cahaya alami, bukan stock yang terlalu mengilap.
6. **Fungsi dulu.** Menu, jam buka, lokasi, dan tombol pesan harus ketemu dalam 3 detik.

---

## 2. Palet Warna

### Warna Inti

| Nama | Hex | Peran |
|---|---|---|
| **Espresso** | `#2B1D14` | Teks utama, footer, section gelap |
| **Roast** | `#4A3224` | Heading sekunder, hover gelap |
| **Caramel** | `#B5753A` | Aksen utama, tombol primer, link |
| **Terracotta** | `#B4552F` | Aksen kuat, badge, highlight promo |
| **Sage** | `#7A8467` | Aksen sejuk, label "vegan", status sukses |
| **Cream** | `#F4ECDD` | Background utama |
| **Oat** | `#E8DCC4` | Background kartu, section selang-seling |
| **Kraft** | `#CDB48F` | Border, divider, ilustrasi |
| **Charcoal** | `#1E1A17` | Background gelap penuh (hero malam, footer) |

### Aturan Pemakaian

- **Proporsi:** 60% Cream/Oat · 25% Espresso/Roast · 10% Caramel · 5% Terracotta/Sage.
- Caramel di atas Cream **hanya untuk teks besar (≥ 18px bold)** atau elemen dekoratif. Untuk teks kecil pakai Roast atau Espresso.
- Jangan pakai putih murni (`#FFF`) atau hitam murni (`#000`). Gunakan `#FFFDF8` dan Espresso.

### CSS Variables

```css
:root {
  /* Warna */
  --color-espresso: #2B1D14;
  --color-roast: #4A3224;
  --color-caramel: #B5753A;
  --color-terracotta: #B4552F;
  --color-sage: #7A8467;
  --color-cream: #F4ECDD;
  --color-oat: #E8DCC4;
  --color-kraft: #CDB48F;
  --color-charcoal: #1E1A17;
  --color-paper: #FFFDF8;

  /* Semantik */
  --bg: var(--color-cream);
  --bg-alt: var(--color-oat);
  --bg-dark: var(--color-charcoal);
  --text: var(--color-espresso);
  --text-muted: #6B5646;
  --text-on-dark: var(--color-cream);
  --accent: var(--color-caramel);
  --accent-strong: var(--color-terracotta);
  --border: var(--color-kraft);
}
```

---

## 3. Tipografi

| Peran | Font | Fallback | Catatan |
|---|---|---|---|
| **Display / Heading** | **Fraunces** (variable, opsz & SOFT) | `Georgia, serif` | Serif hangat dengan karakter "lembut", cocok untuk gaya rustic |
| **Body / UI** | **DM Sans** | `system-ui, sans-serif` | Bersih, modern, mudah dibaca |
| **Aksen Tulisan Tangan** | **Caveat** | `cursive` | Hanya untuk catatan kecil: "baru!", "favorit barista" |

```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Caveat:wght@500;600&family=DM+Sans:wght@400;500;700&family=Fraunces:opsz,wght,SOFT@9..144,400..700,50&display=swap" rel="stylesheet">
```

### Skala Tipe (Desktop → Mobile)

| Token | Desktop | Mobile | Weight | Line-height | Font |
|---|---|---|---|---|---|
| `display` | 72px | 44px | 600 | 1.05 | Fraunces |
| `h1` | 56px | 36px | 600 | 1.1 | Fraunces |
| `h2` | 40px | 30px | 600 | 1.15 | Fraunces |
| `h3` | 28px | 22px | 500 | 1.25 | Fraunces |
| `body-lg` | 20px | 18px | 400 | 1.65 | DM Sans |
| `body` | 17px | 16px | 400 | 1.65 | DM Sans |
| `small` | 14px | 14px | 500 | 1.5 | DM Sans |
| `eyebrow` | 13px | 12px | 700 | 1.2 | DM Sans, uppercase, letter-spacing `0.14em` |
| `handwritten` | 26px | 22px | 600 | 1.2 | Caveat |

**Aturan:** lebar baris teks maksimal **65 karakter** (`max-width: 65ch`). Judul boleh memakai `text-wrap: balance`.

---

## 4. Spasi, Grid, dan Bentuk

### Spacing (basis 8px)

`4 · 8 · 16 · 24 · 32 · 48 · 64 · 96 · 128`

- Jarak antar-section: **96px** desktop / **64px** mobile.
- Padding kartu: **24–32px**.

### Grid

- Container maksimal **1200px**, padding samping 24px (mobile) / 48px (desktop).
- 12 kolom desktop, 4 kolom mobile, gap 24px.
- Layout sengaja dibuat **sedikit asimetris** (teks kiri 5 kolom, foto kanan 7 kolom) agar terasa editorial, bukan template.

### Bentuk

| Token | Nilai | Dipakai untuk |
|---|---|---|
| `--radius-sm` | 6px | Input, badge |
| `--radius-md` | 12px | Kartu, tombol |
| `--radius-lg` | 24px | Gambar besar, modal |
| `--radius-arch` | `200px 200px 12px 12px` | **Foto berbentuk lengkung (arch)**, ciri khas rustic modern |

### Bayangan (hangat, bukan abu-abu)

```css
--shadow-sm: 0 2px 6px rgba(43, 29, 20, 0.08);
--shadow-md: 0 8px 24px rgba(43, 29, 20, 0.12);
--shadow-lg: 0 20px 48px rgba(43, 29, 20, 0.16);
```

### Border

- Garis tipis `1px solid var(--border)`.
- Divider dekoratif: garis putus-putus `1px dashed var(--color-kraft)` (kesan jahitan / label kraft).

---

## 5. Tekstur & Elemen Dekoratif

- **Grain kertas:** overlay noise SVG dengan opacity 4–6% di atas background Cream.
- **Serat kayu:** hanya untuk section gelap atau footer, opacity maksimal 10%.
- **Stempel / badge bulat:** untuk "Single Origin", "Roasted Daily", "Est. 2019" (outline Roast, teks uppercase kecil, sedikit rotasi −6°).
- **Ilustrasi line-art:** biji kopi, ranting, daun, cangkir. Garis 1.5px warna Roast atau Kraft.
- **Label kraft:** bidang Kraft dengan border putus-putus untuk harga dan kategori menu.

```css
.grain::after {
  content: "";
  position: absolute; inset: 0;
  background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='160' height='160'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='2'/></filter><rect width='100%' height='100%' filter='url(%23n)' opacity='0.5'/></svg>");
  opacity: 0.05;
  mix-blend-mode: multiply;
  pointer-events: none;
}
```

---

## 6. Komponen

### 6.1 Navbar

- Background Cream (transparan di atas hero), border-bottom putus-putus Kraft saat di-scroll.
- Kiri: logo (wordmark serif). Tengah/kanan: **Menu · Tentang · Galeri · Kontak**.
- CTA kanan: tombol **"Pesan Sekarang"** (primer).
- Mobile: hamburger → drawer penuh warna Espresso, link berukuran besar (Fraunces 32px).

### 6.2 Tombol

| Varian | Style |
|---|---|
| **Primer** | BG Caramel, teks Espresso (bold), radius 12px, padding `14px 28px`. Hover: BG Terracotta + teks Cream, naik 2px |
| **Sekunder** | Outline 1.5px Roast, teks Roast. Hover: fill Roast + teks Cream |
| **Teks / Link** | Teks Roast, underline Caramel 2px dengan offset 4px. Hover: underline menebal |

- Tinggi minimum **48px** (target sentuh mobile).
- Fokus: `outline: 3px solid var(--color-sage); outline-offset: 3px`.

### 6.3 Kartu Menu

```
┌────────────────────────────┐
│  [foto arch / persegi]     │
│                            │
│  KOPI · eyebrow            │
│  Es Kopi Gula Aren   ·····  Rp 28K  ← label kraft
│  Espresso, susu segar,     │
│  gula aren rumahan.        │
│  [🌱 Vegan option]  [+]    │
└────────────────────────────┘
```

- BG Oat atau Paper, radius 12px, border Kraft 1px.
- Hover: naik 4px, `shadow-md`, foto zoom halus (`scale(1.04)`, 400ms).
- Harga di-set dengan Fraunces, dipisahkan dari nama dengan garis titik-titik (*dotted leader*) seperti menu cetak.

### 6.4 Badge & Tag

- Radius 6px, uppercase 12px, bold, letter-spacing 0.1em.
- **Baru** = Terracotta · **Favorit** = Caramel · **Vegan** = Sage · **Seasonal** = Roast.

### 6.5 Form (Reservasi / Kontak)

- Input: BG Paper, border Kraft, radius 6px, tinggi 48px.
- Fokus: border Caramel + ring 3px Caramel 25%.
- Label di atas input, DM Sans 14px bold.
- Error: teks Terracotta + ikon, jangan hanya mengandalkan warna.

### 6.6 Testimoni

- Kutipan Fraunces italic 24px, tanda kutip besar Caramel (opacity 30%).
- Nama pengulas memakai gaya Caveat sebagai "tanda tangan".

### 6.7 Footer

- BG Charcoal, teks Cream, link hover Caramel.
- 4 kolom: Brand & tagline · Jam buka · Lokasi · Sosial media/Newsletter.
- Garis divider putus-putus Kraft 30%.

---

## 7. Struktur Halaman (Homepage)

| # | Section | Isi & Gaya |
|---|---|---|
| 1 | **Hero** | Display headline besar di kiri, foto arch (barista menuang kopi) di kanan. 2 CTA: *Lihat Menu* & *Reservasi*. Badge stempel berputar pelan di sudut foto |
| 2 | **Strip Info** | Bar Oat: jam buka · alamat singkat · status "Buka sekarang" (titik hijau Sage) |
| 3 | **Cerita Kami** | Foto kolase 2–3 gambar (sedikit overlap & rotasi 2°) + paragraf singkat + teks tulisan tangan |
| 4 | **Menu Andalan** | Grid 3 kolom, tab kategori (Kopi · Non-Kopi · Makanan · Pastry) |
| 5 | **Proses / Biji Kami** | 3 langkah ikonik: *Petik → Sangrai → Seduh*. Background Espresso, teks Cream |
| 6 | **Galeri** | Masonry 6–8 foto, hover memberi caption kecil |
| 7 | **Testimoni** | Carousel 3 kutipan, BG Oat |
| 8 | **Reservasi / Kontak** | Form kiri, peta & info kanan |
| 9 | **Footer** | Seperti 6.7 |

### Contoh Copy

- **Headline:** *"Disangrai pelan, diseduh dengan hati."*
- **Sub:** *Kopi lokal pilihan, roti hangat, dan kursi kayu yang betah diduduki seharian.*
- **CTA:** *Lihat Menu* · *Reservasi Meja*
- **Tone:** ramah, tenang, sedikit puitis. Hindari jargon dan tanda seru berlebihan.

---

## 8. Fotografi & Ilustrasi

- **Cahaya:** natural, hangat, dari jendela. Hindari flash dan filter dingin.
- **Subjek:** tangan barista, uap kopi, biji kopi, cangkir keramik, meja kayu, tekstur karung goni.
- **Grading:** shadow sedikit ke coklat, highlight krem. Saturasi rendah-sedang.
- **Rasio:** 4:5 (portrait/arch), 3:2 (lanskap), 1:1 (menu).
- **Format:** WebP/AVIF, `loading="lazy"`, wajib `alt` deskriptif.
- **Overlay teks di foto:** gradient Espresso 60% → transparan agar kontras aman.

---

## 9. Motion & Interaksi

| Elemen | Efek | Durasi / Easing |
|---|---|---|
| Scroll reveal | Fade-up 16px | 600ms `cubic-bezier(.22,.61,.36,1)` |
| Hover kartu | Naik 4px + shadow | 250ms ease-out |
| Hover tombol | Naik 2px + ganti warna | 200ms |
| Foto menu | Zoom 1.04 | 400ms |
| Stempel badge | Rotasi pelan 360° | 30s linear infinite |
| Uap kopi (hero, opsional) | SVG path bergelombang | 4s ease-in-out infinite |

- Animasi **lembut dan lambat**, sesuai suasana santai.
- Hormati preferensi pengguna:

```css
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after { animation: none !important; transition: none !important; }
}
```

---

## 10. Responsif

| Breakpoint | Lebar | Penyesuaian |
|---|---|---|
| `sm` | ≥ 640px | 2 kolom menu |
| `md` | ≥ 768px | Navbar horizontal, hero 2 kolom |
| `lg` | ≥ 1024px | 3 kolom menu, grid penuh |
| `xl` | ≥ 1280px | Container 1200px, display 72px |

- **Mobile-first.** Tombol *Pesan* dibuat sticky di bawah layar pada mobile.
- Foto arch tetap dipertahankan di mobile (lebar 100%, tinggi 380px).

---

## 11. Aksesibilitas

- Kontras teks minimal **WCAG AA (4.5:1)**. Espresso di atas Cream ≈ 13:1 ✅
- Semua elemen interaktif bisa diakses keyboard, dengan fokus yang jelas.
- Jangan sampaikan informasi hanya lewat warna (badge selalu disertai teks/ikon).
- Gunakan elemen semantik: `header`, `nav`, `main`, `section`, `footer`.
- Peta dan carousel punya alternatif teks serta kontrol jeda.

---

## 12. Do & Don't

| ✅ Do | ❌ Don't |
|---|---|
| Pakai warna tanah dan krem hangat | Pakai biru neon, ungu, atau gradient mencolok |
| Beri banyak ruang kosong | Menjejalkan terlalu banyak elemen per layar |
| Tekstur subtil (opacity ≤ 6%) | Tekstur kayu/kertas penuh di belakang teks |
| Serif untuk judul, sans untuk isi | Mencampur lebih dari 3 font |
| Foto nyata dengan cahaya alami | Stock photo glossy atau ikon 3D |
| Animasi pelan dan halus | Animasi cepat, berkedip, atau memutar terus tanpa jeda |

---

## 13. Token Tailwind (Opsional)

```js
// tailwind.config.js
module.exports = {
  theme: {
    extend: {
      colors: {
        espresso: '#2B1D14',
        roast: '#4A3224',
        caramel: '#B5753A',
        terracotta: '#B4552F',
        sage: '#7A8467',
        cream: '#F4ECDD',
        oat: '#E8DCC4',
        kraft: '#CDB48F',
        charcoal: '#1E1A17',
      },
      fontFamily: {
        display: ['Fraunces', 'Georgia', 'serif'],
        sans: ['"DM Sans"', 'system-ui', 'sans-serif'],
        hand: ['Caveat', 'cursive'],
      },
      borderRadius: { arch: '200px 200px 12px 12px' },
      boxShadow: {
        warm: '0 8px 24px rgba(43,29,20,0.12)',
      },
    },
  },
};
```

---

## 14. Checklist Sebelum Rilis

- [ ] Semua halaman memakai token warna dan font di atas
- [ ] Kontras teks lolos AA
- [ ] Gambar dikompres (WebP/AVIF) dan punya `alt`
- [ ] Menu, jam buka, dan lokasi terlihat tanpa scroll berlebihan
- [ ] Tombol *Pesan/Reservasi* ada di navbar dan hero
- [ ] Skor Lighthouse ≥ 90 (Performance & Accessibility)
- [ ] Uji di Chrome, Safari, dan minimal 2 ukuran HP

---

---

# BAGIAN LANJUTAN — Disesuaikan dengan Implementation Plan

> Bagian 1–14 berlaku untuk **halaman publik**. Bagian 15–21 menambahkan panduan untuk **alur pelanggan (OTP, custom menu, pesanan, QR)**, **dashboard kasir**, dan **dashboard admin**.

## 15. Implementasi Teknis (Laravel + Blade + Tailwind)

- **Stack tampilan:** Blade + Tailwind CSS (via Vite), interaksi ringan dengan Alpine.js / Livewire.
- Pindahkan token Bagian 13 ke `tailwind.config.js`; font dimuat di layout utama.
- Pisahkan layout berdasarkan peran:

```
resources/views/
├── layouts/
│   ├── public.blade.php      ← tema rustic penuh (grain, serif, ilustrasi)
│   ├── customer.blade.php    ← turunan public, ditambah menu akun
│   ├── cashier.blade.php     ← mode fungsional (lihat Bagian 18)
│   └── admin.blade.php       ← mode fungsional + sidebar
├── components/               ← Blade components: button, card-menu, badge,
│                               input, otp-input, price-tag, status-pill, modal
├── public/                   ← home, tentang, menu, detail-produk, kontak
├── customer/                 ← register, otp, custom-menu, cart, checkout,
│                               order-code, orders, saved-creations
├── cashier/                  ← orders, verify, walk-in, history
└── admin/                    ← menu, konten, karyawan, laporan
```

- Semua komponen memakai class dari token (`bg-cream`, `text-espresso`, `font-display`), tanpa warna hardcode.
- Update real-time (dashboard kasir) memakai Laravel Reverb/Pusher; UI harus siap menampilkan status koneksi.

## 16. Alur Pelanggan: Daftar & OTP

### 16.1 Form Daftar
- Satu kartu di tengah (maks 440px), BG Paper, radius 12px, shadow-md, ilustrasi line-art cangkir di atas.
- Field: **Nama · Email · Nomor HP** (prefix `+62` tetap, hanya angka).
- Teks bantuan kecil: *"Kami kirim kode ke email dan WhatsApp/SMS-mu."*
- Tombol primer: **"Kirim Kode"**.

### 16.2 Input OTP (komponen `otp-input`)
- **6 kotak terpisah**, masing-masing 52×60px, Fraunces 28px, border Kraft, fokus Caramel.
- Auto-pindah antar kotak, mendukung paste 6 digit sekaligus, `inputmode="numeric"`, `autocomplete="one-time-code"`.
- Dua bagian verifikasi berurutan atau berdampingan: **Kode Email** dan **Kode HP**, masing-masing dengan status ✓ (Sage) saat benar.
- **Countdown 05:00** (DM Sans, angka tabular) menunjukkan sisa masa berlaku.
- Indikator **sisa percobaan**: "Sisa 2 kesempatan" (Terracotta saat ≤ 1).
- Tombol **"Kirim ulang"** nonaktif sampai batas rate-limit selesai, lengkap dengan hitung mundur ("Bisa kirim ulang dalam 0:42").
- Salah kode: kotak bergetar halus (shake 300ms) + pesan hangat, bukan merah agresif.

### 16.3 Microcopy OTP

| Kondisi | Teks |
|---|---|
| Berhasil kirim | "Kode sudah meluncur. Cek email dan WhatsApp-mu ya." |
| Kode salah | "Hmm, kodenya belum cocok. Coba cek lagi." |
| Kedaluwarsa | "Kodenya sudah kedaluwarsa. Yuk minta yang baru." |
| Percobaan habis | "Kesempatan habis. Minta kode baru untuk lanjut." |
| Terlalu sering minta | "Tunggu sebentar sebelum minta kode lagi." |
| Email/HP sudah terdaftar | "Email atau nomor ini sudah punya akun. Masuk saja." |
| Sukses verifikasi | "Selamat datang, [Nama]! Mau pesan apa hari ini?" |

### 16.4 Pengunjung Belum Login
- Boleh membuka Custom Menu. Saat menekan **Simpan** atau **Pesan**, tampil modal ramah: *"Daftar dulu yuk, biar kreasimu tersimpan."* dengan pilihan **Daftar / Masuk**. Pilihan kreasi tidak hilang (simpan sementara di session).

## 17. Halaman Pelanggan

### 17.1 Custom Menu Builder (Berkreasi)
Halaman andalan. Dibuat seperti "meja barista" yang interaktif.

- **Layout desktop:** kiri 7 kolom = langkah pilihan; kanan 5 kolom = **panel ringkasan lengket (sticky)** berisi preview cangkir, daftar pilihan, dan total harga.
- **Mobile:** panel ringkasan menjadi **bottom bar** (total + tombol "Tambah ke Keranjang"), dapat ditarik ke atas untuk melihat detail.
- **Stepper 5 langkah:** `Base → Ukuran → Gula → Es/Panas → Topping`.
  - Setiap langkah memakai **kartu pilihan** (radio/checkbox visual): BG Oat, terpilih = border Caramel 2px + centang kecil Caramel.
  - Topping bisa multi-select, tampil sebagai *chip* bergaya label kraft.
- **Tingkat gula:** slider bertitik 4 posisi (0 · 25 · 50 · 100%) dengan ikon butir gula.
- **Preview cangkir:** ilustrasi SVG line-art; warna isi cairan berubah sesuai base (espresso gelap, latte krem, matcha sage); es = kotak-kotak kecil; topping muncul sebagai elemen di atas.
- **Harga otomatis:** angka berubah dengan animasi *count* singkat (200ms). Rincian harga tampil rapi (dotted leader).
- **Nama kreasi:** field opsional bergaya tulisan tangan (Caveat), placeholder *"Beri nama kreasimu..."*.
- CTA: **"Tambah ke Keranjang"** (primer) dan **"Simpan Kreasi"** (sekunder, memicu modal login bila belum masuk).

### 17.2 Detail Produk
- Foto arch besar, pilihan **ukuran** (segmented control) dan **add-on** (checkbox chip), stepper jumlah, kolom catatan.
- Produk habis: foto grayscale 60%, badge **"Habis"** (Roast), tombol berubah menjadi nonaktif "Sedang habis".

### 17.3 Keranjang & Checkout
- Keranjang: daftar item bergaya "struk kertas" (BG Paper, tepi bergerigi atas-bawah via CSS mask).
- Ringkasan sticky di kanan (desktop) / bawah (mobile).
- **Pilih jalur pembayaran** dengan dua kartu besar:

| Kartu | Isi | Tombol |
|---|---|---|
| 💳 **Bayar Online** | QRIS, e-wallet, transfer, kartu (sesuai payment gateway) | "Bayar Sekarang" |
| 🧾 **Bayar di Kasir** | "Dapat kode pesanan & QR, bayar saat datang." | "Buat Kode Pesanan" |

- Setelah memilih Bayar Online → halaman/pop-up gateway; status kembali ke halaman pesanan.

### 17.4 Halaman Kode Pesanan & QR (jalur Ke Kasir)
Halaman paling "berkesan". Dibuat seperti **tiket/kupon kraft**.

```
┌ - - - - - - - - - - - - - - - - ┐
│   ☕  [NAMA KEDAI]               │
│   Kode Pesananmu                 │
│                                  │
│        ┌─────────────┐           │
│        │   QR CODE   │           │
│        └─────────────┘           │
│        BREW-4F7K2                │ ← Fraunces 40px, letter-spacing lebar
│  - - - - - - - - - - - - - - -   │
│  2× Es Kopi Gula Aren            │
│  1× Croissant Almond             │
│  Total            Rp 84.000      │
│  Status: ⏳ Menunggu pembayaran   │
│                                  │
│  "Tunjukkan kode ini ke kasir"   │ ← Caveat
└ - - - - - - - - - - - - - - - - ┘
```

- Kartu: BG Kraft/Oat, border putus-putus, **tepi bergerigi**, QR di atas kotak Paper dengan padding 16px.
- QR minimal **200×200px**, kontras tinggi (Espresso di atas Paper), jangan diberi warna aksen.
- Tombol: **Simpan sebagai gambar** · **Salin kode**. Kecerahan layar disarankan naik saat halaman dibuka (info kecil).
- Bisa diakses ulang dari **Riwayat Pesanan**.

### 17.5 Status Pesanan (Timeline)
Garis vertikal dengan titik status:
`Diterima → Diproses → Siap → Selesai` (atau `Dibatalkan`).
- Titik aktif Caramel berdenyut halus; selesai = Sage dengan centang; batal = Terracotta.
- Saat berubah ke **Siap**, tampilkan banner Sage: *"Pesananmu siap diambil! ☕"*.

### 17.6 Riwayat & Kreasi Tersimpan
- Riwayat: daftar kartu ringkas (kode, tanggal, total, status pill) + tombol **"Pesan Lagi"**.
- Kreasi tersimpan: grid kartu dengan preview cangkir mini, nama (Caveat), harga, tombol **Pesan** dan **Hapus**.

## 18. Dashboard Kasir (Mode Fungsional)

> Prioritas: **cepat, jelas, minim salah tekan**. Tema rustic dikurangi: tanpa grain, tanpa ilustrasi dekoratif; palet dan font tetap sama supaya satu identitas.

- **Layout:** `cashier.blade.php`, top bar Espresso (logo kecil, nama kasir, shift aktif, status koneksi real-time 🟢/🔴), area utama BG Cream.
- **Tipografi:** DM Sans mendominasi; Fraunces hanya untuk angka total dan judul halaman. Ukuran isi **minimal 16px**, tombol aksi **minimal 56px tinggi** (cocok untuk tablet).
- **Papan pesanan (kanban 4 kolom):**

| Kolom | Isi | Warna header |
|---|---|---|
| Menunggu Bayar | Pesanan jalur "Ke Kasir" | Kraft |
| Diterima | Sudah dibayar, belum dikerjakan | Caramel |
| Diproses | Sedang dibuat | Terracotta |
| Siap | Menunggu diambil | Sage |

- **Kartu pesanan:** kode (Fraunces 20px bold), nama pelanggan / "Walk-in", daftar item, catatan (disorot kuning-krem bila ada), waktu tunggu (berubah Terracotta bila > 10 menit), tombol aksi satu langkah ("Proses", "Siap", "Selesai").
- **Pesanan baru:** kartu masuk dengan animasi slide + **nada notifikasi** (dapat dimatikan) + badge "Baru" 10 detik.
- **Verifikasi:** kolom input besar **"Masukkan / scan kode"** di atas papan, mendukung kamera (scan QR) dan keyboard. Hasil: kartu pesanan muncul dengan tombol **"Terima Pembayaran"**.
- **Pembayaran:** modal dengan pilihan Tunai / Non-tunai. Tunai menyediakan kalkulator kembalian (input uang diterima → kembalian tampil besar). Setelah sukses: **struk** (gaya kertas) dengan tombol Cetak.
- **Input walk-in:** halaman grid produk (kartu besar, tap untuk tambah), panel keranjang di kanan, tanpa perlu akun pelanggan.
- **Tandai menu habis:** toggle cepat di daftar produk; efek langsung terlihat di halaman publik.
- **Riwayat harian:** tabel bersih, filter kasir/shift, total penjualan per metode bayar.
- Aksi destruktif (batal pesanan) selalu meminta konfirmasi dengan alasan.

## 19. Dashboard Admin (Mode Fungsional)

- **Layout:** sidebar kiri Espresso (240px) berisi menu: Ringkasan · Menu · Konten · Karyawan · Laporan · Pengaturan. Mobile: sidebar menjadi drawer.
- **Ringkasan:** 4 kartu metrik (Penjualan hari ini, Jumlah pesanan, Menu terlaris, Pesanan online vs kasir), grafik garis penjualan 7 hari (warna Caramel, area Oat), daftar menu terlaris.
- **Tabel data:** header Oat, baris selang-seling Cream/Paper, hover Oat 50%, aksi di kolom terakhir (ikon edit & hapus + tooltip).
- **CRUD Menu:** form 2 kolom (info dasar | opsi ukuran & add-on dinamis), unggah foto dengan pratinjau arch, toggle ketersediaan.
- **Konten Profil:** editor sederhana untuk teks beranda, jam buka, lokasi, galeri (drag untuk urutan).
- **Karyawan:** daftar akun, role pill, tombol aktif/nonaktif, log aktivitas kasir (tabel dengan filter tanggal).
- **Laporan:** filter harian / mingguan / bulanan, ekspor CSV/PDF.

## 20. Sistem Status & Feedback

### Status Pesanan (pill)

| Status | BG | Teks | Ikon |
|---|---|---|---|
| Menunggu bayar | Kraft | Espresso | ⏳ |
| Dibayar | Oat | Roast | 💳 |
| Diterima | Caramel 20% | Roast | 📥 |
| Diproses | Terracotta 20% | Terracotta | 🔥 |
| Siap | Sage 25% | `#4F5A3E` | ✓ |
| Selesai | Sage | Cream | ✓✓ |
| Dibatalkan | Charcoal 10% | Charcoal | ✕ |

- Selalu pasangkan **ikon + teks**, jangan hanya warna.
- **Toast/notifikasi:** pojok kanan atas (desktop) / atas tengah (mobile), BG Paper + border kiri 4px sesuai tipe (Sage sukses, Terracotta error, Caramel info), hilang otomatis 5 detik.
- **Loading:** skeleton Oat dengan shimmer halus; tombol memakai spinner kecil bergaya biji kopi.
- **Empty state:** ilustrasi line-art + kalimat hangat (mis. keranjang kosong: *"Cangkirmu masih kosong. Yuk pilih sesuatu."*).

## 21. Keamanan yang Tampak di UI

- Halaman dashboard tidak memakai tautan dari navbar publik; customer yang mengakses URL kasir melihat halaman **403** bernuansa kopi: *"Area ini khusus barista."*
- Indikator **sesi kasir** (nama + shift) selalu tampak di top bar; tombol **Keluar** mudah dijangkau.
- Sesi kedaluwarsa → modal "Masuk kembali" tanpa kehilangan data yang sedang diisi.
- Field sensitif (OTP, password) memakai `autocomplete` yang tepat dan tidak pernah tampil di URL.
- Pesan error otentikasi tidak membocorkan apakah email/HP terdaftar (kecuali pada alur daftar yang memang perlu memberi tahu).

## 22. Checklist Tambahan Sebelum Rilis

- [ ] Alur end-to-end: daftar → OTP → custom menu → checkout → bayar/QR → kasir → selesai
- [ ] OTP: countdown, batas percobaan, rate-limit, dan paste 6 digit berfungsi
- [ ] QR terbaca dari layar HP dengan kecerahan rendah dan di bawah cahaya toko
- [ ] Dashboard kasir nyaman di tablet 10" (tombol ≥ 56px)
- [ ] Notifikasi real-time bekerja dan punya fallback polling bila koneksi putus
- [ ] Customer tidak bisa membuka halaman kasir/admin (cek per role)
- [ ] Halaman 403, 404, 500 bertema dan punya tombol kembali
- [ ] Status "Habis" dari kasir langsung tampil di halaman publik

---

