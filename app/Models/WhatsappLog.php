<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsappLog extends Model
{
        protected $fillable = [
        'garansi_id', 'tujuan', 'lokasi', 'pesan', 'image_data', 'tipe', 'status_kirim', 'response_api',
    ];

    public function garansi(): BelongsTo
    {
        return $this->belongsTo(Garansi::class);
    }
}