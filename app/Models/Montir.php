<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Montir extends Model
{
    protected $table = 'montir';

    protected $fillable = [
        'bengkel_id',
        'user_id',
        'verifikasi',
    ];

    public function bengkel()
    {
        return $this->belongsTo(Bengkel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
