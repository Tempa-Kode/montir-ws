<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UlasanRating extends Model
{
    protected $table = 'ulasan_rating';

    protected $fillable = [
        'order_layanan_id',
        'pelanggan_id',
        'bengkel_id',
        'rating',
        'ulasan',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(User::class, 'pelanggan_id');
    }

    public function bengkel()
    {
        return $this->belongsTo(Bengkel::class, 'bengkel_id');
    }

    public function orderLayanan()
    {
        return $this->belongsTo(OrderLayanan::class, 'order_layanan_id');
    }
}
