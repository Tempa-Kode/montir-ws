<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UlasanRatingMontir extends Model
{
    protected $table = 'ulasan_rating_montir';

    protected $fillable = [
        'order_layanan_id',
        'pelanggan_id',
        'montir_id',
        'rating',
        'ulasan',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(User::class, 'pelanggan_id');
    }

    public function montir()
    {
        return $this->belongsTo(User::class, 'montir_id');
    }

    public function orderLayanan()
    {
        return $this->belongsTo(OrderLayanan::class, 'order_layanan_id');
    }
}
