<?php

namespace App\Observers;

use App\Models\Garansi;
use App\Services\WhatsappService;
use Illuminate\Support\Facades\Log;

class GaransiObserver
{
    public function created(Garansi $garansi): void
    {
    }

    public function updated(Garansi $garansi): void
    {
        if ($garansi->wasChanged('status') && $garansi->status === 'selesai') {
            try {
                $garansi->load('items');
                app(WhatsappService::class)->sendStatusNotification($garansi);
            } catch (\Exception $e) {
                Log::error("Gagal kirim WA status notification: " . $e->getMessage());
            }
        }
    }
}