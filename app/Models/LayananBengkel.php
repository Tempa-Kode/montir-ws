<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LayananBengkel extends Model
{
    public $table = 'layanan_bengkel';
    protected $fillable = [
        'bengkel_id',
        'jenis_layanan',
        'harga',
    ];

    public function bengkel()
    {
        return $this->belongsTo(Bengkel::class, 'bengkel_id');
    }
}
