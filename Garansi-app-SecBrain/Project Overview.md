# Garansi App Overview

← Kembali ke **[[Welcome]]**

## Deskripsi Singkat
Aplikasi **Garansi App** adalah sebuah sistem berbasis web untuk melacak klaim garansi produk. Aplikasi ini memiliki dua sisi utama:
1. **Dashboard Admin/Teknisi**: Untuk mengelola data garansi, memeriksa status perbaikan, dan berkomunikasi dengan pelanggan (via *Bubble Chat*).
2. **Halaman Publik Customer (Tracking)**: Pelanggan dapat memasukkan *Serial Number* (SN) atau Nama & Nomor HP untuk melihat status garansi produknya dan dapat berkomunikasi secara *real-time* dengan teknisi tanpa harus login.

Lihat juga: **[[Database Schema]]** | **[[Changelog]]**

## Teknologi Utama
- **Backend**: Laravel 13 (PHP)
- **Frontend**: Blade Templates, Tailwind CSS v4 (via Vite), Alpine.js untuk interaksi dinamis (seperti chat dan drag-and-drop).
- **Database**: MySQL/MariaDB (dikelola lewat Laravel Migrations & Eloquent).

## Peran (Roles)
- **Admin**: Memiliki kontrol penuh, bisa menghapus history chat, dan mengatur data global.
- **Teknisi**: Bisa merespons chat dari pelanggan dan mengubah status (misal dari `pending` -> `repair` -> `replace` -> `pengiriman`).
- **Customer**: Pengguna anonim yang mengakses via halaman `/cek-garansi`.

## Arsitektur & Fitur Kunci
1. **Alur Status Percabangan (Branching)**
   Status klaim garansi memiliki alur logika:
   `Pending` -> `Repair` / `Replace` / `Distribusi` -> `Pengiriman` -> `Selesai`
   
2. **Fitur Chat Real-time**
   - **Teknisi**: Melalui *Floating Bubble Chat* di pojok kanan bawah layar (menggunakan Alpine.js polling ke endpoint `garansi.chat.active`).
   - **Customer**: Melalui panel chat yang disematkan langsung di halaman *Tracking*.

3. **Sistem Peringatan Kadaluwarsa Sesi**
   Karena menggunakan AJAX polling, sistem telah disempurnakan untuk menangani sesi *login expired* dengan mengembalikan *header 401* sehingga menghindari *bug* halaman mentah (*raw JSON*) muncul di layar browser.

## Direktori Penting
- `app/Http/Controllers/GaransiChatController.php`: Inti dari fitur percakapan.
- `app/Http/Controllers/TrackingController.php`: Mengurus logika pencarian halaman publik.
- `resources/views/tracking/`: Memuat semua komponen UI untuk tampilan publik (termasuk timeline dinamis dan chat UI customer).
- `resources/views/bublechat.blade.php`: Komponen *floating chat* untuk teknisi.
