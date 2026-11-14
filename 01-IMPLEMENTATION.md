# Implementation Plan: Website Coffee Shop

## 1. Ringkasan
Website untuk coffee shop dengan dua sisi: halaman **publik** (company profile, menu, custom menu, pemesanan) dan halaman **internal** untuk **karyawan/kasir** (sistem kasir). Pelanggan wajib mendaftar dengan email dan nomor HP terverifikasi OTP sebelum bisa memesan.

## 2. Tujuan
- Memperkenalkan coffee shop lewat company profile.
- Memudahkan pelanggan melihat menu dan membuat minuman kustom.
- Memungkinkan pemesanan lewat website (bayar online) atau datang ke kasir dan menunjukkan kode pesanan.
- Memberi karyawan alat kasir yang sederhana dan cepat.

## 3. Peran Pengguna

| Peran | Akses |
|---|---|
| Pengunjung (belum daftar) | Company profile, melihat menu, membuat custom menu (tanpa checkout) |
| Customer (terverifikasi OTP) | Semua di atas + pesan, bayar online, simpan custom menu, riwayat pesanan |
| Kasir/Karyawan | Login internal, daftar pesanan masuk, proses pembayaran, tandai selesai |
| Admin/Owner | Kelola menu, harga, akun karyawan, laporan penjualan |

## 4. Fitur Halaman Publik

### 4.1 Company Profile
- Halaman beranda, tentang kami, lokasi dan jam buka, kontak, galeri.
- Konten dikelola admin dari dashboard.

### 4.2 Menu
- Daftar menu per kategori (kopi, non-kopi, makanan, snack).
- Detail produk: foto, deskripsi, harga, pilihan ukuran dan add-on.
- Status ketersediaan (habis/tersedia) yang bisa diubah kasir atau admin.

### 4.3 Custom Menu (Berkreasi)
- Pelanggan memilih base (espresso, susu, dll), ukuran, tingkat gula, es/panas, dan topping.
- Harga dihitung otomatis dari pilihan.
- Pengunjung bisa mencoba. Untuk menyimpan atau memesan, wajib login/daftar.
- Customer dapat menyimpan kreasi favorit dan membagikannya (opsional, fase lanjut).

### 4.4 Pemesanan dan Pembayaran
Ada dua jalur:
1. **Bayar online**: keranjang → checkout → pembayaran (payment gateway, misalnya Midtrans atau Xendit) → status pesanan "dibayar".
2. **Ke kasir**: pelanggan membuat pesanan, lalu mendapat **kode pesanan** dan **QR code**. Di kasir, pelanggan menunjukkan QR/kode, kasir memverifikasi dan memproses pembayaran di tempat.

## 5. Pendaftaran dan Login dengan OTP

### 5.1 Alur
1. Pengguna isi nama, email, dan nomor HP.
2. Sistem kirim OTP (6 digit) ke email **dan** nomor HP (WhatsApp atau SMS).
3. Pengguna memasukkan OTP. OTP berlaku 5 menit dan maksimal 3 kali percobaan.
4. Akun aktif setelah verifikasi. Login berikutnya memakai email atau nomor HP + OTP.

### 5.2 Aturan
- Satu email dan satu nomor HP hanya bisa terdaftar satu akun.
- Batasi permintaan OTP (misalnya maksimal 3 kali per 10 menit) untuk mencegah spam.
- OTP disimpan dalam bentuk hash, tidak plain text.
- Session login memakai token dengan masa berlaku.

## 6. Fitur Kasir (Karyawan)
- Login karyawan (email + password, atau OTP internal).
- Dashboard pesanan masuk secara real-time (pesanan online yang sudah dibayar dan pesanan kasir yang menunggu bayar).
- Verifikasi pesanan dengan kode atau scan QR.
- Input pesanan manual untuk pelanggan yang datang langsung tanpa akun.
- Proses pembayaran tunai atau non-tunai, cetak atau tampilkan struk.
- Ubah status pesanan: diterima → diproses → siap → selesai, atau batal.
- Tandai menu habis.
- Riwayat transaksi harian per kasir.

## 7. Fitur Admin
- CRUD menu, kategori, add-on, dan harga.
- Kelola konten company profile.
- Kelola akun karyawan dan hak akses.
- Laporan penjualan harian, mingguan, bulanan, dan menu terlaris.

## 8. Model Data (ringkas)
- `users`: id, nama, email (unik), no_hp (unik), email_verified_at, phone_verified_at, role (customer/kasir/admin), created_at
- `otp_codes`: id, user_id/target, channel (email/sms), code_hash, expired_at, attempts
- `categories`, `products`, `product_options` (ukuran, add-on), `product_availability`
- `custom_menus`: id, user_id, nama, base, ukuran, pilihan (JSON), harga
- `orders`: id, kode_pesanan, user_id (nullable untuk walk-in), source (online/kasir), status, total, created_at
- `order_items`: order_id, product/custom_menu, qty, harga, catatan
- `payments`: order_id, metode, status, referensi_gateway, dibayar_at
- `cashier_shifts`: kasir_id, mulai, selesai, total_penjualan

## 9. Teknologi (usulan)
- **Backend**: Laravel (konsisten dengan proyek toko online kerudung) dengan Sanctum untuk autentikasi API.
- **Frontend publik**: Blade + Tailwind CSS, responsif untuk mobile, dengan Alpine.js atau Livewire untuk custom menu dan keranjang.
- **Dashboard kasir dan admin**: halaman terpisah dengan Livewire atau Vue, dengan update real-time via Pusher atau Laravel Reverb.
- **Database**: MySQL.
- **OTP**: email via Mailtrap/SMTP untuk development, Resend/SES untuk production. SMS/WhatsApp via Fonnte, Twilio, atau WhatsApp Business API.
- **Pembayaran**: Midtrans atau Xendit (sandbox dulu).
- **QR code**: library `simplesoftwareio/simple-qrcode`.
- **Deploy**: VPS atau layanan cloud dengan HTTPS, backup database harian.

## 10. Tahapan Implementasi

**Fase 1 - Fondasi (1 minggu)**
- Setup proyek Laravel, autentikasi, role, dan struktur database.
- Modul OTP email dan nomor HP.

**Fase 2 - Publik (1-2 minggu)**
- Company profile dan halaman menu.
- Custom menu builder dengan kalkulasi harga.
- Keranjang dan checkout.

**Fase 3 - Pembayaran dan Kasir (1-2 minggu)**
- Integrasi payment gateway (sandbox).
- Kode pesanan dan QR, dashboard kasir, ubah status pesanan.
- Pesanan walk-in di kasir.

**Fase 4 - Admin dan Laporan (1 minggu)**
- CRUD menu dan konten.
- Manajemen karyawan dan laporan penjualan.

**Fase 5 - Uji dan Rilis (1 minggu)**
- Uji alur end-to-end (daftar, OTP, pesan, bayar, kasir).
- Uji di perangkat mobile.
- Deploy dan pelatihan karyawan.

## 11. Keamanan dan Kualitas
- HTTPS wajib, validasi input di server, proteksi CSRF, dan rate limiting.
- Otorisasi per peran (policy/gate) agar customer tidak bisa mengakses dashboard kasir.
- Log aktivitas kasir untuk audit.
- Data pribadi (email, nomor HP) hanya dipakai untuk akun dan notifikasi pesanan.

## 12. Pertanyaan Terbuka
- Nama brand dan logo coffee shop?
- Metode pembayaran online yang diinginkan (QRIS, e-wallet, transfer, kartu)?
- Apakah ada lebih dari satu cabang?
- Kanal OTP: apakah SMS/WhatsApp sudah ada penyedia pilihan?
- Apakah pelanggan boleh memesan untuk diambil (pickup) saja, atau juga delivery?
