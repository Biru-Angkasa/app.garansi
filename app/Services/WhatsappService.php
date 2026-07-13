<?php

namespace App\Services;

use App\Models\Garansi;
use App\Models\GaransiItem;
use App\Models\WhatsappLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    /**
     * Normalisasi nomor HP ke format 62xxx
     */
    public function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }

    /**
     * Kirim pesan WhatsApp via WAHA API (Dynamic URL per Lokasi)
     */
    public function send(
        string $tujuan,
        string $pesan,
        string $lokasi,
        ?int $garansiId = null,
        string $tipe = 'manual'
    ): array {
        $config = config("whatsapp.lokasi.{$lokasi}");

        if (!$config) {
            return [
                'success' => false,
                'message' => "Lokasi '{$lokasi}' tidak ditemukan di konfigurasi.",
            ];
        }

        $tujuan = $this->normalizePhone($tujuan);
        $chatId = $tujuan . '@c.us';

        $log = WhatsappLog::create([
            'garansi_id'   => $garansiId,
            'tujuan'       => $tujuan,
            'lokasi'       => $lokasi,
            'pesan'        => $pesan,
            'tipe'         => $tipe,
            'status_kirim' => 'pending',
        ]);

        // Ambil URL spesifik per lokasi dari config
        $wahaUrl = rtrim($config['url'], '/') . '/api/sendText';
        $apiKey = $config['api_key'];

        // === DEBUG SEMENTARA ===
        Log::info('=== DEBUG WAHA REQUEST ===', [
            'url'      => $wahaUrl,
            'key_used' => $apiKey ? substr($apiKey, 0, 6) . '...' : 'KOSONG!',
            'session'  => $config['session'],
        ]);
        // ========================

        try {
            $http = Http::timeout(15);

            if (!empty($apiKey)) {
                $http = $http->withHeaders([
                    'X-Api-Key' => $apiKey,
                ]);
            }

            $response = $http->post($wahaUrl, [
                'session'      => $config['session'],
                'chatId'       => $chatId,
                'text'         => $pesan,
            ]);

            $body = $response->body();
            $success = $response->successful();

            if ($success && stripos($body, 'error') !== false && stripos($body, '"sent":true') === false) {
                $success = false;
            }

            $log->update([
                'status_kirim' => $success ? 'terkirim' : 'gagal',
                'response_api' => $body,
            ]);

            return [
                'success' => $success,
                'message' => $success ? 'Pesan terkirim.' : 'Gagal mengirim pesan.',
                'response' => $body,
            ];
        } catch (\Exception $e) {
            $log->update([
                'status_kirim' => 'gagal',
                'response_api' => $e->getMessage(),
            ]);

            Log::error("WAHA API Error ({$lokasi}): " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Gagal terhubung ke WAHA: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Label & ikon status yang konsisten dipakai di semua template pesan.
     * Key HARUS sama persis dengan value kolom `status` di database.
     */
    protected function statusMeta(string $status): array
    {
        return match ($status) {
            'repair' => [
                'label' => 'Perbaikan (Repair)',
                'icon'  => '🔧',
                'desc'  => 'Barang Anda sedang dalam proses perbaikan oleh tim teknisi kami.',
            ],
            'replace' => [
                'label' => 'Penggantian (Replace)',
                'icon'  => '📦',
                'desc'  => 'Barang Anda sedang diproses untuk penggantian unit baru.',
            ],
            'to distribution' => [
                'label' => 'Distribusi',
                'icon'  => '🏭',
                'desc'  => 'Barang Anda sedang dalam proses distribusi ke lokasi tujuan.',
            ],
            'pengiriman' => [
                'label' => 'Pengiriman',
                'icon'  => '🚚',
                'desc'  => 'Barang Anda sedang dalam perjalanan pengiriman.',
            ],
            'selesai' => [
                'label' => 'Selesai',
                'icon'  => '✅',
                'desc'  => "Garansi Anda telah *selesai diproses*.\nSilakan hubungi kami untuk informasi pengambilan atau pengiriman barang.",
            ],
            default => [
                'label' => 'Pending',
                'icon'  => '⏳',
                'desc'  => 'Barang Anda sedang dalam antrian untuk diproses.',
            ],
        };
    }

    /**
     * Pesan otomatis saat data baru dibuat
     */
    public function sendCreateNotification(Garansi $garansi): array
    {
        $items = $garansi->items->map(
            fn ($item) => "• {$item->nama_barang} — SN: {$item->serial_number}"
        )->implode("\n");

        $meta = $this->statusMeta($garansi->status);
        $tanggalSampai = $garansi->tanggal_sampai
            ? $garansi->tanggal_sampai->format('d/m/Y H:i')
            : now()->format('d/m/Y H:i');

        $pesan  = "Halo *{$garansi->nama}* 👋\n";
        $pesan .= "Data garansi Anda sudah kami terima. Berikut ringkasannya:\n";
        $pesan .= "――――――――――――――――\n";
        $pesan .= "📄 *No. Invoice:* {$garansi->invoice_pembelian}\n";
        $pesan .= "📦 *Barang:*\n{$items}\n";
        $pesan .= "🛒 *Marketplace:* {$garansi->nama_marketplace}\n";
        $pesan .= "📅 *Tanggal Beli:* {$garansi->tanggal_beli->format('d/m/Y')}\n";
        $pesan .= "📍 *Lokasi CS:* " . ucfirst($garansi->lokasi_chat) . "\n";
        $pesan .= "⏰ *Tanggal Diterima:* {$tanggalSampai}\n";
        $pesan .= "{$meta['icon']} *Status:* {$meta['label']}\n";
        $pesan .= "――――――――――――――――\n\n";
        $pesan .= "Kami akan mengabari Anda lewat WhatsApp ini setiap kali ada perkembangan status.\n\n";
        $pesan .= "Terima kasih telah mempercayakan garansi Anda kepada kami 🙏";

        return $this->send(
            $garansi->no_hp,
            $pesan,
            $garansi->lokasi_chat,
            $garansi->id,
            'create'
        );
    }

    /**
     * Pesan otomatis saat status berubah
     */
    public function sendStatusNotification(Garansi $garansi): array
    {
        $lokasiNama = config("whatsapp.lokasi.{$garansi->lokasi_chat}.nama") ?? ucfirst($garansi->lokasi_chat);
        $meta = $this->statusMeta($garansi->status);

        $pesan  = "Halo *{$garansi->nama}* 👋\n";
        $pesan .= "Ada pembaruan untuk status garansi Anda:\n";
        $pesan .= "――――――――――――――――\n";
        $pesan .= "📄 *No. Invoice:* {$garansi->invoice_pembelian}\n";
        $pesan .= "📍 *Lokasi CS:* {$lokasiNama}\n";
        $pesan .= "{$meta['icon']} *Status Terbaru:* {$meta['label']}\n";
        $pesan .= "――――――――――――――――\n\n";
        $pesan .= "{$meta['desc']}\n";

        if ($garansi->catatan) {
            $pesan .= "\n📝 *Catatan dari kami:*\n{$garansi->catatan}\n";
        }

        $pesan .= "\nTerima kasih 🙏";

        return $this->send(
            $garansi->no_hp,
            $pesan,
            $garansi->lokasi_chat,
            $garansi->id,
            'status_update'
        );   
    }

    /**
     * Kirim Foto ke WhatsApp via WAHA
     */

    /**
     * Kirim gambar via WAHA.
     *
     * Menerima $base64Image dalam salah satu dari dua bentuk:
     *  - Data URI lengkap:  "data:image/jpeg;base64,/9j/4AAQSkZJRg..."
     *  - Base64 murni:      "/9j/4AAQSkZJRg..."
     *
     * PENTING: field `file.data` yang dikirim ke WAHA HARUS berupa base64 MURNI
     * (tanpa prefix "data:mime;base64,"). Mengirim data URI utuh membuat WAHA
     * men-decode string yang mengandung karakter non-base64 (":", ";", ",")
     * sehingga bytes gambar hasil decode jadi rusak/terpotong — inilah sebab
     * gambar "stuck download" / gagal dibuka di WhatsApp meski status API sukses.
     */
    public function sendImage(
        string $tujuan,
        string $caption,
        string $base64Image,
        string $lokasi,
        ?int $garansiId = null,
        string $tipe = 'photo_update'
    ): array {
        $config = config("whatsapp.lokasi.{$lokasi}");

        if (!$config) {
            return [
                'success' => false,
                'message' => "Lokasi '{$lokasi}' tidak ditemukan di konfigurasi.",
            ];
        }

        // ---- 1. Pisahkan mimetype & base64 murni dari input ----
        $mime = 'image/jpeg';
        $extension = 'jpg';
        $pureBase64 = $base64Image;

        if (preg_match('/^data:(image\/[a-zA-Z0-9.+-]+);base64,(.+)$/s', $base64Image, $matches)) {
            $mime = $matches[1];
            $pureBase64 = $matches[2];

            $extension = match ($mime) {
                'image/png'  => 'png',
                'image/webp' => 'webp',
                'image/gif'  => 'gif',
                default      => 'jpg',
            };
        }

        // Buang whitespace/newline yang kadang ikut ter-copy pada string base64 panjang.
        $pureBase64 = preg_replace('/\s+/', '', $pureBase64);
        $filename = 'bukti.' . $extension;

        // ---- 2. Validasi base64 benar-benar bisa di-decode & tidak kosong ----
        $binary = base64_decode($pureBase64, true);

        if ($pureBase64 === '' || $binary === false || strlen($binary) === 0) {
            Log::error("WAHA Image API Error ({$lokasi}): base64 gambar tidak valid atau kosong.");

            return [
                'success' => false,
                'message' => 'Data gambar tidak valid, gagal dikirim.',
            ];
        }

        // ---- 3. Siapkan tujuan & log awal ----
        $tujuan = $this->normalizePhone($tujuan);
        $chatId = $tujuan . '@c.us';

        $log = WhatsappLog::create([
            'garansi_id'   => $garansiId,
            'tujuan'       => $tujuan,
            'lokasi'       => $lokasi,
            'pesan'        => $caption,
            // Simpan sebagai data URI supaya tetap bisa ditampilkan langsung di <img src="...">
            'image_data'   => 'data:' . $mime . ';base64,' . $pureBase64,
            'tipe'         => $tipe,
            'status_kirim' => 'pending',
        ]);

        $wahaUrl = rtrim($config['url'], '/') . '/api/sendImage';
        $apiKey = $config['api_key'] ?? '';

        try {
            $http = Http::timeout(30);

            if (!empty($apiKey)) {
                $http = $http->withHeaders([
                    'X-Api-Key' => $apiKey,
                ]);
            }

            $response = $http->post($wahaUrl, [
                'session' => $config['session'],
                'chatId'  => $chatId,
                'file'    => [
                    'mimetype' => $mime,
                    'filename' => $filename,
                    // Base64 MURNI, tanpa prefix "data:...;base64,".
                    'data'     => $pureBase64,
                ],
                'caption' => $caption,
            ]);

            $body = $response->body();
            $success = $response->successful();

            $log->update([
                'status_kirim'  => $success ? 'terkirim' : 'gagal',
                'response_api'  => $body,
            ]);

            if (!$success) {
                Log::error("WAHA Image API Error ({$lokasi}): HTTP {$response->status()} - {$body}");
            }

            return [
                'success'  => $success,
                'message'  => $success ? 'Foto berhasil dikirim.' : 'Gagal mengirim foto.',
                'response' => $body,
            ];
        } catch (\Exception $e) {
            $log->update([
                'status_kirim' => 'gagal',
                'response_api' => $e->getMessage(),
            ]);

            Log::error("WAHA Image API Error ({$lokasi}): " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Gagal mengirim foto: ' . $e->getMessage(),
            ];
        }
    }
    /**
     * Pesan otomatis saat SN barang diganti (replace)
     */
    public function sendReplaceNotification(Garansi $garansi, GaransiItem $item): array
    {
        $lokasiNama = config("whatsapp.lokasi.{$garansi->lokasi_chat}.nama") ?? ucfirst($garansi->lokasi_chat);

        $pesan  = "Halo *{$garansi->nama}* 👋\n";
        $pesan .= "Informasi penggantian barang untuk garansi Anda:\n";
        $pesan .= "――――――――――――――――\n";
        $pesan .= "📄 *No. Invoice:* {$garansi->invoice_pembelian}\n";
        $pesan .= "📍 *Lokasi CS:* {$lokasiNama}\n";
        $pesan .= "📦 *Barang:* {$item->nama_barang}\n";
        $pesan .= "🔁 *SN Lama:* {$item->serial_number}\n";
        $pesan .= "🆕 *SN Baru:* {$item->serial_number_baru}\n";
        $pesan .= "――――――――――――――――\n\n";
        $pesan .= "Barang Anda telah diganti dengan unit baru menggunakan Serial Number di atas. Mohon disimpan untuk keperluan garansi selanjutnya.\n\n";
        $pesan .= "Terima kasih 🙏";

        return $this->send(
            $garansi->no_hp,
            $pesan,
            $garansi->lokasi_chat,
            $garansi->id,
            'replace_sn'
        );
    }
}
