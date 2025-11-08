<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderLayanan extends Model
{
    protected $table = 'order_layanan';

    protected $fillable = [
        'montir_id',
        'layanan_bengkel_id',
        'pelanggan_id',
        'latitude',
        'longitude',
        'status',
        'harga_layanan',
        'status_pembayaran',
        'bukti_bayar',
    ];

    public function montir()
    {
        return $this->belongsTo(Montir::class, 'montir_id');
    }

    public function layananBengkel()
    {
        return $this->belongsTo(LayananBengkel::class, 'layanan_bengkel_id');
    }

    public function pelanggan()
    {
        return $this->belongsTo(User::class, 'pelanggan_id');
    }

    public function itemService()
    {
        return $this->hasMany(ItemService::class, 'order_layanan_id');
    }

    public function ulasanRating() 
    {
        return $this->hasOne(UlasanRating::class, 'order_layanan_id');
    }
}
