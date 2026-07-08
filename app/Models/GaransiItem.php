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
        'serial_number_baru',
        'replaced_at',
    ];

    protected $casts = [
        'replaced_at' => 'datetime',
    ];

    public function garansi(): BelongsTo
    {
        return $this->belongsTo(Garansi::class);
    }

    public function getIsReplacedAttribute(): bool
    {
        return !empty($this->serial_number_baru);
    }
}