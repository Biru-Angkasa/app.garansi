# TODO - Redesign `garansi/show.blade.php` (design-taste-frontend-v1)

## Step 1 ✅
- Konfirmasi ruang lingkup: redesain seluruh UI di `resources/views/garansi/show.blade.php` menjadi lebih premium, tanpa mengubah behavior backend/routes.


## Step 2
- Refactor layout & hierarchy:
  - Buat struktur lebih asimetris (split kiri/kanan) untuk hero ringkasan + aksi.
  - Kurangi over-card/inner shadow tebal; gunakan border/divide/whitespace.

## Step 3
- Premium visual polish:
  - Kalibrasi palette: tetap slate/zinc netral + maksimal 1 accent, hindari dominasi purple.
  - Konsistensi radius, spacing, typography.

## Step 4
- Motion & interaksi:
  - Rapikan micro-interactions (tactile active states) untuk tombol utama.
  - Tambahkan skeleton/empty state yang lebih premium.

## Step 5
- Modal update status:
  - Samakan style liquid-glass: border-white/10 + inset shadow.
  - Rapikan field show/hide dan fokus minimal.

## Step 6
- Items & WhatsApp logs:
  - Ubah struktur items menjadi logic-grouped borders/divide.
  - Rapikan bubble log dan image thumb.

## Step 7
- Cleanup JS:
  - Pastikan semua event listener aman (elemen null-checked).
  - Hindari ketergantungan class/style yang berubah untuk fungsi logika.

## Step 8
- Validasi manual:
  - Buka halaman show.
  - Cek modal update status, simpan SN replace, kirim WA manual, resend.

## Step 9
- (Opsional) Jalankan lint/build bila diperlukan.

