# 🦉 Readowl Bookstore (TO_USK)
> **Aplikasi E-Commerce Toko Buku Fisik & Perpustakaan Rak 3D Koleksi Pribadi**  
> Proyek Standar Uji Kompetensi Keahlian / Sertifikasi BNSP Junior Web Developer (JWD).

[![Live Preview](https://img.shields.io/badge/Live_Demo-GitHub_Pages-2ea44f?style=for-the-badge&logo=githubpages&logoColor=white)](https://bannni.github.io/TO_USK/)
![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpinedotjs&logoColor=white)
![Tests](https://img.shields.io/badge/Tests-29%20Passed%20(191%20assertions)-brightgreen?style=for-the-badge&logo=checkmarx&logoColor=white)

🌐 **Live Demo Website:** [https://bannni.github.io/TO_USK/](https://bannni.github.io/TO_USK/)

---

## 📌 Sekilas Proyek (Project Preview)

**Readowl Bookstore** adalah platform web toko buku fisik modern dengan pengalaman visual editorial *curated*. Berbeda dari toko buku digital biasa, Readowl mensimulasikan alur transaksi buku fisik nyata:
1. **Calon Pembeli** memilih buku di toko (`/shop`), membaca preview & sinopsis, memilih item tertentu di keranjang (`/cart`), atau langsung beli (`Buy Now`).
2. **Pesanan Masuk** tercatat dengan status awal `pending` dan stok fisik **belum terpotong**.
3. **Administrator** memverifikasi pesanan di Panel Admin (`/admin`), melakukan persetujuan (**ACC Pesanan**).
4. **Stok Terpotong Otomatis** dari tabel `stocks` dengan mekanisme *pessimistic locking* (`lockForUpdate`).
5. **Buku Masuk ke Rak 3D Pribadi** (`/catalog`) pembeli sebagai bukti kepemilikan buku fisik yang telah dikirimkan ke alamat tujuan.

---

## 🔑 Akun Demo Pengujian (Test Credentials)

Setelah menjalankan seeder database, Anda dapat langsung menguji aplikasi menggunakan akun berikut:

| Peran (Role) | Email | Password | Hak Akses Utama |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin@readowl.com` | `Password@123` | Akses penuh Panel Admin (`/admin`), CRUD Buku & Stok, CRUD Kategori, ACC Pesanan, Log Pengguna, Pesan Kontak |
| **Pembeli (User)** | `pembaca@readowl.com` | `Password@123` | Belanja Buku (`/shop`), Keranjang (`/cart`), Beli Langsung, Pesanan Saya (`/my-orders`), Rak Buku (`/catalog`) |

> *Catatan: Anda juga dapat mendaftarkan akun pembeli baru melalui halaman [Registrasi](http://127.0.0.1:8000/register) dengan validasi nomor telepon internasional/nasional dan kombinasi kata sandi aman.*

---

## ✨ Fitur-Fitur Unggulan

### 🛍️ 1. Pengalaman Belanja (Storefront & Pembeli)
* **Preview Sinopsis & Rincian Buku**: Klik sampul atau tombol *Preview* di toko buku untuk melihat rincian penulis, penerbit, tahun terbit, rating, jumlah halaman, dan ketersediaan stok fisik secara langsung.
* **Keranjang Belanja Pintar dengan Checkbox**:
  * Checkbox *Pilih Semua* (*Select All*) dan per-item buku.
  * Hanya buku yang dicentang yang diproses saat *checkout*; buku yang tidak dipilih tetap tersimpan aman di keranjang.
  * Perhitungan subtotal dan total biaya reaktif secara *real-time* via Alpine.js.
  * Validasi batas stok maksimum agar jumlah pesanan tidak melebihi stok yang ada.
* **Beli Langsung (1-Click Direct Buy)**:
  * Modal pembelian cepat dengan stepper kuantitas yang dibatasi maksimum sesuai stok buku.
  * Input **Alamat Pengiriman Lengkap** wajib diisi (minimal 10 karakter) untuk pengiriman kurir ke rumah.
* **Pilihan Metode Pembayaran Beragam**:
  * QRIS (Instant)
  * Transfer Bank BCA / Mandiri
  * E-Wallet (GoPay / OVO)
  * Cash on Delivery (COD / Bayar di Tempat)
* **Perpustakaan Rak Buku Fisik (3D My Library)**:
  * Tampilan buku artistik yang hanya menampilkan buku pesanan yang telah disetujui (ACC).
  * Dilindungi middleware autentikasi dan pencegah *back-cache* browser setelah logout.

### 🛡️ 2. Panel Kurator Administrator (`/admin`)
* **Dashboard Statistik Real-Time**: Total koleksi buku, nilai kapital inventaris, total pengguna, kategori genre, dan pesanan aktif.
* **CRUD Master Buku & Inventaris Stok Terpisah**:
  * Validasi judul unik (*tidak boleh ada judul ganda*).
  * Pengunggahan berkas sampul (*storage disk*) atau URL gambar eksternal dengan fitur **Live Image Preview**.
  * Pengelolaan kuantitas stok di tabel `stocks` secara terisolasi.
* **CRUD Kategori Buku**: Pengelolaan kategori dengan pembuatan slug otomatis dan penghitungan relasi buku.
* **Manajemen & ACC Status Pesanan**:
  * Pengubahan status pesanan: `pending` $\rightarrow$ `processing` (ACC) $\rightarrow$ `completed` $\rightarrow$ `cancelled`.
  * Pemotongan stok otomatis saat ACC dan pengembalian (*restore*) stok otomatis saat pesanan dibatalkan.
* **Monitoring Pengguna & Kotak Pesan Kontak**: Pemantauan data pengguna terdaftar dan manajemen pesan masuk.

### 🔒 3. Keamanan & Kualitas Kode
* **Middleware Pembatas Hak Akses Dua Arah (*Bidirectional Role Barrier*)**:
  * `AdminMiddleware`: Menolak akses user biasa ke area `/admin/*`.
  * `UserOnlyMiddleware`: Menolak akun admin dari halaman belanja user (`/shop`, `/cart`, `/catalog`).
* **Proteksi Riwayat Cache Browser (`PreventBackHistoryMiddleware`)**: Mencegah tombol *Back* browser menampilkan data akun sebelumnya setelah logout.
* **Integritas Transaksi Database**: Menggunakan `DB::beginTransaction()` dan `lockForUpdate()` untuk mencegah *race condition* atau *overselling*.
* **Bebas Kedipan Modal (*Anti-FOUC*)**: Menggunakan styling `[x-cloak]` dan deklarasi inline agar tidak ada modal yang berkedip saat memuat ulang halaman.

---

## 🚀 Panduan Menjalankan Proyek Secara Mandiri (Local Setup)

Ikuti langkah-langkah mudah berikut untuk menjalankan dan menguji proyek ini di komputer lokal Anda:

### 1. Klon Repositori
```bash
git clone https://github.com/Bannni/TO_USK.git
cd TO_USK
```

### 2. Pasang Dependensi Composer
```bash
composer install
```

### 3. Konfigurasi Environment
Salin berkas `.env.example` menjadi `.env`:
```bash
# Windows PowerShell / CMD:
copy .env.example .env

# Linux / macOS:
cp .env.example .env
```
Generate application key:
```bash
php artisan key:generate
```

### 4. Migrasi Database & Seeder
Proyek ini secara default telah dikonfigurasi menggunakan SQLite (atau MySQL sesuai pengaturan `.env` Anda). Jalankan migrasi dan seeder untuk membuat tabel dan mengisi akun demo serta katalog buku:
```bash
php artisan migrate --seed
```

### 5. Buat Symbolic Link Storage
Agar berkas gambar sampul buku yang diunggah dapat diakses publik:
```bash
php artisan storage:link
```

### 6. Jalankan Server Lokal
```bash
php artisan serve
```
Buka peramban (*browser*) Anda dan akses:
👉 **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 🧪 Pengujian Otomatis (Automated Testing)

Proyek ini dilengkapi dengan rangkaian pengujian unit dan fitur komprehensif yang mencakup skenario keamanan, alur stok, registrasi, batas otorisasi, dan transaksi belanja.

Jalankan perintah berikut untuk menguji seluruh fitur:
```bash
php artisan test
```

**Hasil Pengujian:**
```text
   PASS  Tests\Unit\ExampleTest
   PASS  Tests\Feature\CartSelectionAndDirectBuyTest
   PASS  Tests\Feature\ExampleTest
   PASS  Tests\Feature\RegistrationPhoneValidationTest
   PASS  Tests\Feature\RoleBoundaryBarrierTest
   PASS  Tests\Feature\ShelfPhysicalBookTest
   PASS  Tests\Feature\StockManagementTest

  Tests:    29 passed (191 assertions)
  Duration: ~8s
```

---

## 🗄️ Struktur Basis Data Utama

```text
├── users (id, name, email, phone, password, role [admin/user])
├── categories (id, name, slug)
├── books (id, category_id, title [UNIQUE], author, publisher, price, synopsis, cover_url, cover_image, rating, pages, published_year)
├── stocks (id, book_id [FK UNIQUE], quantity)
├── orders (id, order_code, user_id, book_id, quantity, total_price, payment_method, status, stock_deducted, shipping_address, buyer_notes)
├── order_items (id, order_id, book_id, quantity, price)
├── carts (id, user_id, book_id, quantity)
└── contacts (id, name, email, subject, message, is_read)
```

---

## 👨‍💻 Pengembang
* **Nama**: Bannni (Assyabanni)
* **Repositori GitHub**: [https://github.com/Bannni/TO_USK](https://github.com/Bannni/TO_USK)
* **Lisensi**: Proyek ini dilisensikan di bawah [MIT License](LICENSE).
