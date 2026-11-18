# Design System: Layout Fungsional Polos

## 1. Filosofi Desain

Sistem antarmuka **Fungsional Polos** mengutamakan kesederhanaan, keterbacaan, dan kecepatan alur kerja pengguna (tanpa ornamen dekoratif, tanpa badge berputar/miring, dan tanpa tekstur visual berat).

**Prinsip Utama:**
- **Fungsional & Bersih**: Fokus utama pada kejelasan informasi menu, alur pemesanan, dan transaksi kasir.
- **Tipografi Standar Sistem**: Menggunakan font sans-serif standar sistem operasi (`system-ui`, `-apple-system`, `Segoe UI`, `Roboto`, `sans-serif`) untuk rendering cepat dan keterbacaan maksimal.
- **Palet Netral**: Latar belakang putih dan abu-abu netral dengan aksen biru fungsional untuk tombol tindakan utama.
- **Aksesibilitas & Responsif**: Terbaca jelas pada perangkat seluler, tablet, maupun layar desktop kasir.

---

## 2. Palet Warna (Neutral Functional)

| Peran | Hex | Tailwind Class | Penggunaan |
|---|---|---|---|
| Background Utama | `#F9FAFB` / `#FFFFFF` | `bg-gray-50` / `bg-white` | Latar belakang halaman dan kartu |
| Teks Utama | `#111827` | `text-gray-900` | Judul, nama menu, harga |
| Teks Sekunder | `#6B7280` | `text-gray-500` | Deskripsi, label, catatan |
| Border | `#E5E7EB` | `border-gray-200` | Garis pemisah, batas kartu |
| Aksi Utama (Primary) | `#2563EB` | `bg-blue-600` / `text-blue-600` | Tombol beli, tautan aktif |
| Sukses | `#16A34A` | `bg-green-100` / `text-green-800` | Status pesanan siap, lunas |
| Peringatan / Batal | `#DC2626` | `bg-red-100` / `text-red-800` | Menu habis, pembatalan |

---

## 3. Tipografi & Tata Letak

1. **Heading**: Sans-serif tegas, tebal (`font-bold`, `text-gray-900`), tanpa efek serif dekoratif.
2. **Body**: Sans-serif bersih (`text-sm`, `text-gray-600`), spasi baris proporsional (`leading-relaxed`).
3. **Kartu Menu**: Grid responsif (1 kolom di mobile, 2 kolom di tablet, 3–4 kolom di desktop) dengan batas abu-abu netral tipis.
4. **Formulir**: Input bersih dengan border abu-abu (`border-gray-300`), sudut rounded lembut (`rounded-md`), dan highlight fokus biru.
5. **Struk & Tiket**: Format kartu bersih dan terpusat dengan QR code SVG dan kode pesanan berukuran besar agar mudah dibaca kasir.
