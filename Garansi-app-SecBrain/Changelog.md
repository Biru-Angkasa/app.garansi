# Log Perubahan (Changelog)

← Kembali ke **[[Welcome]]** | Detail proyek: **[[Project Overview]]**

Dokumen ini melacak perbaikan dan fitur terbaru yang diimplementasikan pada project **Garansi App**.

## [Versi Terbaru] - Layout & Timeline Update (Juli 2026)

### Halaman Tracking Customer
- **Timeline Dinamis**: Timeline sekarang menggunakan logika percabangan. Jika status adalah `replace` (ganti unit), sistem akan melompati langkah `repair` dan langsung menampilkan info ganti unit.
- **Resi Pengiriman**: Fitur tambahan untuk menampilkan `resi_pengiriman` kepada *customer* jika admin sudah memasukkan nomor resi pada sistem.
- **Catatan Teknisi**: Menambahkan komponen khusus untuk menampilkan instruksi resmi atau catatan (`catatan`) kepada *customer* di luar lingkup pesan chat biasa.
- **Optimasi Layout Mobile**: Mengubah desain daftar item garansi (`garansi_items`) dari bentuk tumpukan vertikal (yang memakan tempat) menjadi berjejer horizontal (*flex side-by-side*) sehingga lebih kompak dan memberikan sensasi native app di perangkat seluler.

### Admin & Teknisi Dashboard (Bug Fixes)
- **Desain Grid Dashboard**: Memaksa penggunaan inline style `align-items: stretch` dan `margin-top: auto` pada komponen kartu (*cards*) daftar pelanggan di halaman dashboard agar kotak pelanggan memiliki tinggi yang seragam dan rapi walau statusnya berbeda-beda.
- **Perbaikan Nested Form**: Memperbaiki *HTML validation bug* di mana tombol "Hapus" yang menggunakan elemen `<form>` bersarang (nested) di dalam `<a href>` utama pembungkus card. `<a href>` diganti dengan `<div onclick="...">` untuk mencegah rusaknya *grid layout*.
- **Pagination**: Menambah jumlah item per halaman dari 10 menjadi 20 pada Controller.
- **Perbaikan Sesi Chat Kadaluwarsa (Session Expired Polling Bug)**: Mengatasi masalah di mana sistem *polling* dari Alpine.js secara diam-diam me-redirect admin ke halaman login (kemudian ke `/chats/active` yang memunculkan raw JSON). Hal ini diperbaiki dengan menyisipkan _header_ AJAX standar (`X-Requested-With: XMLHttpRequest`) sehingga sistem me-reload halaman dengan aman saat sesi kedaluwarsa.
- **Perbaikan Error 419 Page Expired**: Memodifikasi handler Exception (`bootstrap/app.php`) untuk menangkap `TokenMismatchException` agar sistem secara otomatis me-redirect mundur (*back*) dengan notifikasi yang lebih ramah di halaman login.
