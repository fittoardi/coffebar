# ☕ CoffeBar — Aplikasi Kedai Kopi Artisan (Rustic Edition)

Aplikasi web pemesanan dan kasir kedai kopi (*artisan coffee shop*) berbasis **Laravel 12 / PHP 8.4** dengan gaya visual **Rustic Artisan** yang hangat, otentik, dan elegan.

---

## 📌 Fitur Utama

### 1. Area Publik (Pelanggan)
- **Company Profile**: Beranda dengan cerita kedai, jam operasional, galeri suasana, dan lokasi.
- **Katalog Menu Interaktif**: Filter kategori (Kopi Klasik, Manual Brew, Non-Kopi, Makanan), pencarian langsung, dan status ketersediaan (*live availability*).
- **Custom Menu Builder (Berkreasi)**: Pembuat racikan kopi kustom dengan kalkulator harga instan berbasis pilihan bahan dasar (*espresso/milk*), ukuran, level gula, es/panas, serta aneka topping.
- **Keranjang & Checkout**: Dukungan catatan pesanan per item, ringkasan rincian biaya, serta pilihan pembayaran online (Midtrans / QRIS) atau bayar langsung di kasir (*counter pickup*).
- **Tiket Pesanan Vintage**: Tiket bergaya konser antik lengkap dengan **QR Code** untuk pemindaian di kasir dan struk cetak kasir (*monospace printable thermal receipt*).

### 2. Autentikasi & Verifikasi OTP
- Registrasi wajib verifikasi nomor WhatsApp/HP dan Email melalui kode OTP 6 digit.
- OTP disimpan dalam bentuk enkripsi hash (*non-plain text*), kedaluwarsa 5 menit, batas percobaan maksimal 3x, dan pembatasan frekuensi (*rate limiting* 3 permintaan per 10 menit).
- Adapter multi-kanal SMS/WhatsApp: `LogSmsProvider` (mode dev), `FonnteSmsProvider`, dan `TwilioSmsProvider`.

### 3. Sistem Kasir & Barista (`/kasir`)
- **Dashboard Pesanan Real-Time**: Daftar pesanan masuk dipisahkan berdasarkan tab status (*Perlu Verifikasi, Sedang Diproses, Siap Diambil, Selesai*).
- **Point of Sale (POS) Walk-In**: Input cepat pesanan langsung untuk pelanggan di tempat tanpa registrasi akun, hitung kembalian tunai otomatis, dan cetak struk kasir.
- **Verifikasi Tiket QR & Kode Pesanan**: Pemindaian QR code atau pencarian kode pesanan tiket dengan satu klik untuk verifikasi pembayaran dan proses pembuatan.
- **Buka & Tutup Shift Kasir**: Rekonsiliasi modal awal, penjualan tunai, dan perhitungan selisih kas fisik.
- **Toggle Ketersediaan Menu**: Barista dapat langsung mengubah status menu habis (*sold out*) atau tersedia.

### 4. Panel Admin & Pemilik (`/admin`)
- **Dashboard Ringkasan Bisnis**: Omzet harian, omzet bulanan, jumlah pesanan masuk, leaderboard 5 menu terlaris, dan transaksi terbaru.
- **Manajemen Kategori & Menu Produk**: CRUD lengkap untuk kategori, produk, harga dasar, foto, serta varian ukuran dan add-on dinamis.
- **Manajemen Akun Staf**: Penambahan dan pengelolaan akun kasir dan administrator dengan verifikasi instan.
- **Laporan Penjualan & Ekspor CSV**: Filter rentang waktu (hari ini, 7 hari terakhir, bulan ini, atau tanggal kustom), metrik AOV (*Average Order Value*), pemisahan omzet online vs kasir, serta ekspor berkas CSV UTF-8 yang kompatibel dengan Microsoft Excel dan Google Sheets.

---

## 🎨 Desain Visual (Rustic Style)

Sistem ini didesain sesuai spesifikasi `03-DESIGN.md`:
- **Warna Utama**:
  - `#3E1F0D` (Roasted Wood / Rustic Brown)
  - `#2A1208` (Dark Espresso)
  - `#F5E6C8` (Warm Parchment)
  - `#B87333` (Warm Copper / Kuningan)
  - `#8B3A2A` (Terracotta / Brick Red)
- **Tipografi**:
  - *Headings*: `Playfair Display` & `Libre Baskerville`
  - *Body Text*: `Lora`
  - *Data / Kode / Tiket*: `Courier Prime` / Font Monospace
- **Karakter Visual**: Bayangan offset koper (`shadow-rustic-sm`), border tiket berlubang (*perforated ticket border*), dan lencana stempel kedai (*rustic stamps*).

---

## 🔑 Kredensial Akun Pengujian (Demo)

Kata sandi default untuk seluruh akun pengujian adalah: `password123`

| Peran (Role) | Alamat Email | Nomor HP / WA | Akses Portal |
|---|---|---|---|
| **Owner / Admin** | `admin@coffebar.id` | `081234567890` | `/admin` & `/kasir` |
| **Kasir / Barista** | `kasir@coffebar.id` | `081234567891` | `/kasir` |
| **Pelanggan** | `customer@coffebar.id` | `081234567892` | Halaman Publik / Pesan |

---

## 🚀 Panduan Instalasi & Menjalankan Aplikasi

### 1. Prasyarat Sistem
- PHP 8.4 (ekstensi `pdo_mysql`, `pdo_sqlite`, `curl`, `mbstring`, `fileinfo`, `gd`/`imagick` aktif)
- Composer 2.x
- Node.js 18+ & NPM
- Database MySQL / MariaDB (atau SQLite untuk testing)

### 2. Langkah Setup

```bash
# 1. Masuk ke direktori proyek
cd d:/Laravel/coffebar

# 2. Salin environment & generate app key (jika belum ada)
cp .env.example .env
php artisan key:generate

# 3. Konfigurasi .env (Database & Port)
# Pastikan DB_DATABASE=coffebar dan port database sudah sesuai

# 4. Jalankan migrasi dan seeder database
php artisan migrate:fresh --seed

# 5. Kompilasi aset antarmuka (Tailwind & Alpine)
npm install
npm run build

# 6. Jalankan server lokal
php artisan serve
```

Aplikasi dapat diakses pada browser di:
- **Halaman Publik**: `http://localhost:8000/`
- **Portal Kasir**: `http://localhost:8000/kasir`
- **Panel Admin**: `http://localhost:8000/admin`

---

## 🧪 Menjalankan Pengujian Otomatis (Test Suite)

Aplikasi dilengkapi dengan rangkaian **64 Pest PHP Tests** yang mencakup:
- `OtpServiceUnitTest` (OTP generation, hashing, expiration, rate limiting)
- `AuthFeatureTest` (Registrasi, verifikasi OTP multi-kanal, login sesi, otorisasi role)
- `PublicMenuFeatureTest` (Katalog, filter kategori, detail produk)
- `CartFeatureTest` (Tambah ke keranjang, custom item, update qty)
- `OrderAndCheckoutFeatureTest` (Checkout online & kasir, pembentukan tiket QR)
- `CashierOrderAndWorkflowFeatureTest` (Verifikasi QR di kasir, workflow status pesanan, toggle menu)
- `CashierPosAndShiftFeatureTest` (POS walk-in pesanan langsung, buka/tutup shift kasir)
- `MidtransPaymentFeatureTest` (Webhook payment callback, update status bayar otomatis)
- `AdminMenuAndCategoryFeatureTest` (Akses proteksi admin, CRUD kategori, CRUD menu & varian)
- `AdminStaffAndReportsFeatureTest` (CRUD staf, laporan keuangan, unduh CSV stream)

Jalankan pengujian dengan perintah:

```bash
php vendor/bin/pest
```

Hasil pengujian: **64 passed (238 assertions)**.

---

## 🛡️ Keamanan & Standar Koding
- **Pint Code Style**: Mematuhi format standar Laravel Pint (`vendor/bin/pint --format agent`).
- **CSRF & Rate Limiting**: Proteksi CSRF di seluruh form web (kecuali endpoint webhook pembayaran `/webhook/midtrans`).
- **Sanctum & Hash Guards**: Token API dan OTP aman berbasis hash sha256.
