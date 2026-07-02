<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Garansi extends Model
{
    protected $table = 'garansis';

    protected $fillable = [
        'nama',
        'no_hp',
        'so_number',
        'tanggal_beli',
        'nama_marketplace',
        'kerusakan',
        'kelengkapan_barang',
        'lokasi_chat',
        'status',
        'catatan',
        'tanggal_sampai',
    ];

    protected $casts = [
        'tanggal_beli'   => 'date',
        'tanggal_sampai' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(GaransiItem::class);
    }

    public function whatsappLogs(): HasMany
    {
        return $this->hasMany(WhatsappLog::class);
    }

    // Helper untuk label status
    public function getStatusLabelAttribute(): string
    {
        return ucfirst($this->status);
    }

    // Helper untuk warna badge status
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending'         => 'bg-gray-100 text-gray-800',
            'repair'          => 'bg-blue-100 text-blue-800',
            'replace'         => 'bg-purple-100 text-purple-800',
            'to distribution' => 'bg-yellow-100 text-yellow-800',
            'pengiriman'      => 'bg-indigo-100 text-indigo-800',
            'selesai'         => 'bg-green-100 text-green-800',
            default           => 'bg-gray-100 text-gray-800',
        };
    }
}