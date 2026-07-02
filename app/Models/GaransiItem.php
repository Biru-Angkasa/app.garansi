<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GaransiItem extends Model
{
    protected $fillable = [
        'garansi_id',
        'nama_barang',
        'serial_number',
    ];

    public function garansi(): BelongsTo
    {
        return $this->belongsTo(Garansi::class);
    }
}