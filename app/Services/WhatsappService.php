<?php

namespace App\Services;

use App\Models\Garansi;
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
     * Pesan otomatis saat data baru dibuat
     */
        public function sendCreateNotification(Garansi $garansi): array
    {
        $items = $garansi->items->map(function ($item) {
            return "- {$item->nama_barang} (SN: {$item->serial_number})";
        })->implode("\n");

        $pesan = "Halo *{$garansi->nama}*,\n";
        $pesan .= "Data garansi Anda telah dibuat dengan detail berikut:\n\n";
        $pesan .= "📄 Invoice Pembelian: *{$garansi->invoice_pembelian}*\n";
        $pesan .= "📦 Barang:\n{$items}\n\n";
        $pesan .= "🛒 Marketplace: {$garansi->nama_marketplace}\n";
        $pesan .= "📅 Tanggal Beli: {$garansi->tanggal_beli->format('d/m/Y')}\n";
        $pesan .= "📍 Lokasi CS: " . ucfirst($garansi->lokasi_chat) . "\n";
        $pesan .= "🔄 Status: *{$garansi->status}*\n";
        $tanggalSampai = $garansi->tanggal_sampai ? $garansi->tanggal_sampai->format('d/m/Y H:i') : now()->format('d/m/Y H:i');
        $pesan .= "⏰ Tanggal Sampai: {$tanggalSampai}\n\n";
        $pesan .= "Kami akan memberi tahu Anda jika ada pembaruan status.\n";
        $pesan .= "Terima kasih 🙏";

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
        // Ambil nama lokasi dari config
        $lokasiNama = config("whatsapp.lokasi.{$garansi->lokasi_chat}.nama") ?? ucfirst($garansi->lokasi_chat);
        
        $pesan = "Halo *{$garansi->nama}*,\n\n";
        $pesan .= "Status garansi Anda telah diperbarui:\n\n";
        $pesan .= "📄 Invoice Pembelian: *{$garansi->invoice_pembelian}*\n";
        $pesan .= "📍 Lokasi CS: {$lokasiNama}\n";
        $pesan .= "🔄 Status Terbaru: *" . strtoupper($garansi->status) . "*\n\n";

        // Keterangan dinamis berdasarkan status
        switch ($garansi->status) {
            case 'repair':
                $pesan .= "🔧 Barang Anda sedang dalam proses *Perbaikan (Repair)*.\n";
                break;
            case 'replace':
                $pesan .= "📦 Barang Anda sedang dalam proses *Penggantian (Replace)*.\n";
                break;
            case 'to distribution':
                $pesan .= "🏭 Barang sedang dalam proses *Distribusi*.\n";
                break;
            case 'pengiriman':
                $pesan .= "🚚 Barang Anda sedang dalam proses *Pengiriman*.\n";
                break;
            case 'selesai':
                $pesan .= "✅ Garansi Anda telah *SELESAI*.\n";
                $pesan .= "Silakan hubungi kami untuk informasi pengambilan/pengiriman barang.\n";
                break;
            default:
                $pesan .= "⏳ Barang Anda sedang antrian untuk diproses.\n";
                break;
        }

        if ($garansi->catatan) {
            $pesan .= "\n📝 Catatan: {$garansi->catatan}\n";
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
}