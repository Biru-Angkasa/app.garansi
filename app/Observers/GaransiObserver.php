<?php

namespace App\Observers;

use App\Models\Garansi;
use App\Services\WhatsappService;
use Illuminate\Support\Facades\Log;

class GaransiObserver
{
    public function created(Garansi $garansi): void
    {
        // Dikosongkan, karena kirim WA create dipindah ke Controller 
        // agar data barang (items) sudah pasti tersimpan dulu.
    }

    public function updated(Garansi $garansi): void
    {
        // Dikosongkan, karena kirim WA update status (teks/foto) 
        // sudah dihandle langsung di GaransiController@updateStatus.
    }
}