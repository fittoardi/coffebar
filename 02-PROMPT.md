# Prompt: Membangun Website Coffee Shop

Gunakan dokumen `implementation.md` sebagai acuan utama. Bangun aplikasi sesuai spesifikasi berikut.

## Konteks
Saya ingin membangun website untuk coffee shop dengan dua bagian: halaman publik untuk pengunjung dan pelanggan, serta sistem kasir internal untuk karyawan. Bahasa antarmuka: Bahasa Indonesia. Mata uang: Rupiah (format Rp 12.000).

## Teknologi
- Laravel (versi terbaru yang stabil), PHP 8.2+
- MySQL
- Blade + Tailwind CSS, Alpine.js atau Livewire untuk interaksi
- Laravel Sanctum untuk autentikasi
- Layanan OTP: email (SMTP/Mailtrap saat development) dan SMS/WhatsApp (buat adapter agar provider mudah diganti)
- Payment gateway: Midtrans sandbox (buat sebagai service terpisah)
- Library QR code: simplesoftwareio/simple-qrcode

## Langkah 1: Struktur Proyek
1. Buat proyek Laravel baru dan konfigurasi `.env.example`.
2. Buat migrasi dan model untuk: users (dengan role), otp_codes, categories, products, product_options, custom_menus, orders, order_items, payments, cashier_shifts.
3. Buat seeder berisi contoh kategori dan menu kopi, non-kopi, makanan, dan snack.

## Langkah 2: Autentikasi dengan OTP
Buat alur pendaftaran dan login:
1. Pengguna memasukkan nama, email, dan nomor HP.
2. Sistem membuat OTP 6 digit, menyimpan hash-nya, dan mengirimkannya ke email dan nomor HP.
3. OTP berlaku 5 menit, maksimal 3 percobaan salah.
4. Batasi permintaan OTP maksimal 3 kali per 10 menit per email atau nomor.
5. Email dan nomor HP harus unik.
6. Login berikutnya memakai email atau nomor HP lalu OTP.

Tulis unit test untuk: kedaluwarsa OTP, batas percobaan, dan batas permintaan ulang.

## Langkah 3: Halaman Publik
Buat halaman berikut dengan desain bersih, hangat, dan responsif untuk mobile:
- **Beranda dan company profile**: hero, tentang kami, lokasi dan jam buka, kontak, galeri.
- **Menu**: daftar per kategori dengan filter, detail produk, pilihan ukuran dan add-on, status habis.
- **Custom menu (Berkreasi)**: pilih base, ukuran, gula, es atau panas, dan topping. Harga dihitung otomatis secara real-time. Pengunjung bisa mencoba, tetapi untuk menyimpan atau memesan harus login.
- **Keranjang dan checkout**: pilih bayar online atau ambil di kasir.
- **Pesanan saya**: riwayat, status, dan QR code pesanan.

Untuk pembayaran ke kasir: setelah pesanan dibuat, tampilkan kode pesanan (misalnya CF-20417) dan QR code yang bisa ditunjukkan ke kasir.

## Langkah 4: Sistem Kasir
Buat area `/kasir` yang hanya bisa diakses role kasir dan admin:
- Dashboard pesanan masuk (online yang sudah dibayar dan pesanan menunggu bayar di kasir), diperbarui otomatis tanpa refresh.
- Verifikasi pesanan dengan input kode atau scan QR.
- Input pesanan walk-in untuk pelanggan tanpa akun.
- Proses pembayaran tunai atau non-tunai, lalu tampilkan struk yang bisa dicetak.
- Ubah status: diterima, diproses, siap, selesai, atau batal.
- Tombol tandai menu habis.
- Riwayat transaksi per kasir dan ringkasan shift.

## Langkah 5: Panel Admin
- CRUD menu, kategori, add-on, dan harga.
- Edit konten company profile.
- Kelola akun karyawan dan role.
- Laporan penjualan harian, mingguan, dan bulanan, serta menu terlaris. Sediakan ekspor CSV.

## Langkah 6: Keamanan
- Otorisasi per role dengan Policy atau Gate. Customer tidak boleh mengakses `/kasir` atau `/admin`.
- Validasi semua input di server, proteksi CSRF, rate limiting untuk OTP dan login.
- Hash OTP, jangan simpan dalam bentuk teks biasa.
- Catat aktivitas kasir (siapa mengubah status atau memproses pembayaran, dan kapan).

## Langkah 7: Pengujian dan Dokumentasi
- Tulis feature test untuk alur: daftar, OTP, buat custom menu, checkout, verifikasi kode di kasir, dan bayar.
- Buat `README.md` berisi cara instalasi, konfigurasi `.env`, menjalankan seeder, dan akun demo untuk kasir dan admin.

## Batasan
- Jangan menyimpan data kartu atau data pembayaran sensitif di database. Gunakan token dari payment gateway.
- Gunakan kode yang bersih, dengan nama variabel dan fungsi yang jelas dalam Bahasa Inggris, serta komentar dan teks antarmuka dalam Bahasa Indonesia.
- Jika ada keputusan yang belum jelas (misalnya provider SMS), buat adapter dengan implementasi dummy dan tandai dengan TODO.

## Output yang Diharapkan
1. Struktur folder proyek.
2. Kode migrasi, model, controller, dan route.
3. Komponen Blade atau Livewire untuk setiap halaman.
4. Seeder dan contoh data.
5. Test dasar untuk alur OTP dan pemesanan.
6. Instruksi instalasi di `README.md`.
