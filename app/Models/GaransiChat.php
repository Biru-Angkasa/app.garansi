<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class GaransiChat extends Model
{
    protected $fillable = ['garansi_id', 'user_id', 'sender_name', 'sender_type', 'message', 'is_read'];
    public function garansi()
    {
        return $this->belongsTo(Garansi::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}