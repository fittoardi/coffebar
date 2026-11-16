# ☕ CoffeBar — Aplikasi Kedai Kopi Artisan (Rustic Edition)

Aplikasi web pemesanan dan kasir kedai kopi (*artisan coffee shop*) berbasis **Laravel 12 / PHP 8.4** dengan gaya visual **Rustic Artisan** yang hangat, otentik, dan elegan. Dilengkapi dengan sistem pemesanan online/kasir, verifikasi tiket QR Code vintage, custom menu builder real-time, integrasi payment gateway Midtrans sandbox, panel kasir & POS walk-in, panel administrasi & laporan keuangan, serta konfigurasi **AI Agent Skills**.

---

## 📌 Fitur Utama Aplikasi

### 1. Area Publik & Pelanggan
- **Company Profile**: Beranda dengan narasi kedai, jam operasional, galeri suasana, dan lokasi terintegrasi Google Maps (dapat diedit langsung oleh Admin).
- **Katalog Menu Interaktif**: Filter kategori (Kopi Klasik, Manual Brew, Non-Kopi, Makanan), pencarian langsung, dan status ketersediaan live (*Tersedia / Habis*).
- **Custom Menu Builder (Berkreasi)**: Pembuat racikan kopi kustom dengan kalkulator harga instan berbasis pilihan bahan dasar (*espresso/milk*), ukuran cangkir, tingkat kemanisan gula, suhu (es/panas), serta aneka topping.
- **Keranjang & Checkout**: Dukungan catatan pesanan per item, ringkasan rincian biaya, serta pilihan pembayaran online (Midtrans / QRIS / GoPay / Bank Transfer) atau bayar langsung di kasir (*counter pickup*).
- **Tiket Pesanan Vintage**: Tiket bergaya konser antik lengkap dengan **QR Code** untuk pemindaian di kasir dan struk cetak kasir (*monospace printable thermal receipt*).

### 2. Autentikasi & Verifikasi OTP Multi-Kanal
- Registrasi wajib verifikasi nomor WhatsApp/HP dan Email melalui kode OTP 6 digit.
- OTP disimpan dalam bentuk enkripsi hash (*non-plain text*), kedaluwarsa 5 menit, batas percobaan maksimal 3x, dan pembatasan frekuensi (*rate limiting* 3 permintaan per 10 menit per target).
- Adapter multi-kanal SMS/WhatsApp:
  - `LogSmsProvider` (mode dev: kode OTP tercatat pada `storage/logs/laravel.log`).
  - `FonnteSmsProvider` (WhatsApp gateway via Fonnte API).
  - `TwilioSmsProvider` (SMS gateway via Twilio REST API).

### 3. Sistem Kasir & Barista Internal (`/kasir`)
- **Dashboard Pesanan Real-Time**: Daftar pesanan masuk dipisahkan berdasarkan tab status (*Perlu Verifikasi, Sedang Diproses, Siap Diambil, Selesai*).
- **Point of Sale (POS) Walk-In**: Input cepat pesanan langsung untuk pelanggan di tempat tanpa registrasi akun, hitung kembalian tunai otomatis, dan cetak struk kasir termal.
- **Verifikasi Tiket QR & Kode Pesanan**: Pemindaian QR code atau pencarian kode pesanan tiket dengan satu klik untuk verifikasi pembayaran dan proses pembuatan minuman.
- **Buka & Tutup Shift Kasir**: Rekonsiliasi modal awal di laci kas, total penjualan tunai, dan perhitungan selisih kas fisik harian.
- **Toggle Ketersediaan Menu**: Barista dapat langsung mengubah status menu habis (*sold out*) atau tersedia dari dashboard.

### 4. Panel Admin & Pemilik (`/admin`)
- **Dashboard Ringkasan Bisnis**: Omzet harian, omzet bulanan, jumlah pesanan masuk, leaderboard 5 menu terlaris, dan transaksi terbaru.
- **Manajemen Kategori Menu**: Tambah, ubah, urutkan (*sort order*), ikon emoji, dan hapus kategori (dilengkapi proteksi jika kategori memiliki produk).
- **Manajemen Produk & Varian (Options)**: CRUD lengkap produk, harga dasar, foto, serta repeater dinamis Alpine.js untuk varian ukuran, add-on, dan topping.
- **Manajemen Akun Staf**: Penambahan dan pengelolaan akun kasir dan administrator dengan verifikasi instan.
- **Laporan Penjualan & Ekspor CSV**: Filter rentang waktu (hari ini, 7 hari terakhir, bulan ini, atau tanggal kustom), metrik AOV (*Average Order Value*), pemisahan omzet online vs kasir, serta ekspor berkas CSV UTF-8 yang kompatibel dengan Microsoft Excel dan Google Sheets.
- **Pengaturan Profil Kedai**: Pengelolaan narasi beranda publik (nama kedai, tagline, hero headline, kutipan, cerita kedai, alamat, jam buka, kontak WhatsApp, dan URL Google Maps).

---

## 🎨 Desain Visual (Rustic Style)

Sistem ini didesain sesuai spesifikasi `03-DESIGN.md`:
- **Palet Warna Rustic**:
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
| **Pelanggan** | `customer@coffebar.id` | `081234567892` | `/` (Halaman Publik) |

---

## 🤖 AI Agent Skills & Tooling (`.agents/`)

Proyek ini telah dikonfigurasi dengan kumpulan *Agent Skills* standar yang sinkron dengan proyek `BloomingKembang`:

| Skill | Direktori | Deskripsi & Kegunaan |
|---|---|---|
| **deploying-to-cloud** | `.agents/skills/deploying-to-cloud/` | Manajemen & deploy aplikasi ke Laravel Cloud via CLI |
| **hyperframes** | `.agents/skills/hyperframes/` | Animasi dan render video berbasis HTML |
| **hyperframes-cli** | `.agents/skills/hyperframes-cli/` | Toolkit HyperFrames CLI |
| **infer-conventions** | `.agents/skills/infer-conventions/` | Inferensi dan analisis konvensi arsitektur proyek |
| **laravel-best-practices** | `.agents/skills/laravel-best-practices/` | Panduan arsitektur Laravel, Eloquent, performa DB, caching & keamanan |
| **livewire-development** | `.agents/skills/livewire-development/` | Komponen reaktif Livewire, SFC/MFC, dan lifecycle hooks |
| **tailwindcss-development** | `.agents/skills/tailwindcss-development/` | Utilitas styling layout antarmuka dengan Tailwind CSS |
| **testing-best-practices** | `.agents/skills/testing-best-practices/` | Standar perancangan tes unit dan fitur dengan Pest/PHPUnit |
| **ui-taste** | `.agents/skills/ui-taste/` | Panduan polish estetika UI, typography, dan anti-slop guidelines |
| **volt-development** | `.agents/skills/volt-development/` | Komponen Livewire single-file Volt |

Juga terpasang **MCP Server** `laravel-boost` pada `.agents/mcp_config.json` yang menyediakan perkakas artisan, inspeksi skema database, pencarian dokumentasi resmi, dan resolusi URL absolut.

---

## ⚙️ Konfigurasi Environment (`.env`)

Contoh variabel konfigurasi kunci pada berkas `.env`:

```ini
APP_NAME=CoffeBar
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database MariaDB / MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=coffebar
DB_USERNAME=root
DB_PASSWORD=

# Adapter OTP SMS / WhatsApp (pilihan: log, fonnte, twilio)
SMS_PROVIDER=log
FONNTE_TOKEN=your_fonnte_token_here
TWILIO_SID=your_twilio_sid
TWILIO_TOKEN=your_twilio_auth_token
TWILIO_FROM=+1234567890

# Midtrans Payment Gateway (Sandbox)
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxxxx
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

---

## 🚀 Panduan Instalasi & Menjalankan Aplikasi

### 1. Prasyarat Sistem
- **PHP 8.4** (ekstensi `pdo_mysql`, `pdo_sqlite`, `curl`, `mbstring`, `fileinfo`, `gd`/`imagick` aktif)
- **Composer 2.x**
- **Node.js 18+ & NPM**
- **Database MySQL / MariaDB**

### 2. Langkah Setup

```bash
# 1. Masuk ke direktori proyek
cd d:/Laravel/coffebar

# 2. Salin environment & generate app key (jika belum ada)
cp .env.example .env
php artisan key:generate

# 3. Jalankan migrasi dan seeder awal
php artisan migrate:fresh --seed

# 4. Hubungkan symbolic link storage (untuk berkas upload)
php artisan storage:link

# 5. Kompilasi aset antarmuka (Tailwind v4 & Alpine.js)
npm install
npm run build

# 6. Jalankan server aplikasi lokal
php artisan serve
```

Aplikasi dapat dibuka pada browser:
- **Halaman Depan (Publik)**: [http://localhost:8000/](http://localhost:8000/)
- **Sistem Kasir & POS**: [http://localhost:8000/kasir](http://localhost:8000/kasir)
- **Panel Administrasi**: [http://localhost:8000/admin](http://localhost:8000/admin)

---

## 🧪 Menjalankan Pengujian Otomatis (Test Suite)

Aplikasi dilengkapi dengan rangkaian **68 Pest PHP Tests** tanpa kegagalan:

| Berkas Pengujian | Cakupan Fitur |
|---|---|
| `EndToEndCoffeeShopWorkflowTest.php` | Pengujian menyeluruh (*end-to-end*): registrasi, OTP, custom menu, checkout, buka shift kasir, verifikasi tiket QR, pembayaran tunai, cetak struk, tutup shift kasir, dan matriks keamanan role |
| `OtpServiceUnitTest.php` | Algoritma OTP hash, kedaluwarsa 5 menit, batas percobaan 3x, dan rate limiting |
| `AuthFeatureTest.php` | Alur registrasi, validasi OTP email & HP, sesi login, proteksi role |
| `PublicMenuFeatureTest.php` | Tampilan beranda, katalog menu per kategori, filter pencarian |
| `CartFeatureTest.php` | Keranjang belanja, penambahan menu kustom, manipulasi kuantitas item |
| `OrderAndCheckoutFeatureTest.php` | Checkout pesanan online & kasir, pembentukan tiket QR code |
| `CashierOrderAndWorkflowFeatureTest.php` | Pemindaian tiket QR di kasir, perpindahan status pesanan, stok habis |
| `CashierPosAndShiftFeatureTest.php` | Transaksi walk-in tunai POS, perhitungan kembalian, buka/tutup shift kasir |
| `MidtransPaymentFeatureTest.php` | Callback webhook Midtrans (settlement, pending, expire) & update status |
| `AdminMenuAndCategoryFeatureTest.php` | Proteksi role admin, CRUD kategori, CRUD menu & varian dinamis |
| `AdminStaffAndReportsFeatureTest.php` | Manajemen akun staf, laporan keuangan, unduh CSV, & profil kedai |

Jalankan seluruh pengujian:

```bash
php vendor/bin/pest
```

Hasil: **68 passed (302 assertions)**.

---

## 🛡️ Standar Koding & Keamanan
- **Laravel Pint**: Seluruh kode PHP diformat dengan Laravel Pint (`vendor/bin/pint --format agent`).
- **CSRF Protection**: Proteksi token CSRF di seluruh form web (kecuali endpoint `/webhook/midtrans`).
- **Sanctum & Hash Guards**: OTP disimpan dengan hash `sha256` (tidak plain text) dan token API berbasis Laravel Sanctum.
