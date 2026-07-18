# Skema Database

← Kembali ke **[[Welcome]]** | Detail proyek: **[[Project Overview]]**

Berikut ini adalah struktur tabel (Database Schema) utama yang digunakan dalam **Garansi App** berdasarkan file *migrations* terbaru:

## Tabel `users`
Digunakan untuk login Teknisi dan Admin.
- `id`
- `name`
- `email`
- `password`
- `role` *(ENUM: admin, teknisi)*

## Tabel `garansis`
Menyimpan data klaim garansi yang diajukan oleh *customer*.
- `id`
- `nama`
- `no_hp`
- `status` *(VARCHAR: pending, repair, replace, to distribution, pengiriman, selesai)*
- `lokasi_chat` *(Platform asal komplain)*
- `invoice_pembelian`
- `resi_pengiriman`
- `catatan` *(Catatan resmi dari admin/teknisi)*
- Kolom tambahan dinamis via migration.

## Tabel `garansi_items`
Satu garansi bisa mencakup beberapa produk/item.
- `id`
- `garansi_id` *(Foreign Key -> garansis.id)*
- `nama_barang`
- `serial_number` *(SN asal)*
- `serial_number_baru` *(SN baru apabila status barang diganti / direplace)*

## Tabel `garansi_chats`
Menyimpan riwayat obrolan antara Teknisi dan Customer.
- `id`
- `garansi_id` *(Foreign Key -> garansis.id)*
- `user_id` *(Nullable, terisi jika sender adalah teknisi/admin)*
- `sender_name` *(Nama pengirim, bisa nama teknisi atau nama customer)*
- `sender_type` *(ENUM: teknisi, customer, admin)*
- `message` *(LONGTEXT)*
- `is_read` *(BOOLEAN, untuk menandai notifikasi belum dibaca di floating chat)*

## Tabel Opsional/Log
- `activity_log`: Mencatat histori perubahan dari model untuk auditing.
- `whatsapp_logs`: Berpotensi digunakan untuk mengirim notifikasi status garansi otomatis (WhatsApp Gateway).
