<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Garansi extends Model
{
    use LogsActivity; // <-- TAMBAHKAN INI

    protected $table = 'garansis';

    protected $fillable = [
        'nama', 'no_hp', 'invoice_pembelian', 'tanggal_beli', 'nama_marketplace',
        'kerusakan', 'kelengkapan_barang', 'lokasi_chat', 'status', 'catatan', 'tanggal_sampai', 'sn_pengganti', 'resi_pengiriman',
    ];

    protected $casts = [
        'tanggal_beli'   => 'date',
        'tanggal_sampai' => 'datetime',
    ];

    // Konfigurasi Audit Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'nama',
                'status',
                'kerusakan',
                'catatan',
                'lokasi_chat',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Data garansi telah di-{$eventName}");
    }
    protected static function booted()
    {
        parent::booted();

        static::deleted(function ($garansi) {
            \Spatie\Activitylog\Models\Activity::query()
                ->where('subject_type', self::class)
                ->where('subject_id', $garansi->id)
                ->where('description', 'Data garansi telah di-deleted')
                ->delete();
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(GaransiItem::class);
    }

    public function whatsappLogs(): HasMany
    {
        return $this->hasMany(WhatsappLog::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return ucfirst($this->status);
    }

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