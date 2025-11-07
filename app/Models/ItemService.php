<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemService extends Model
{
    protected $table = 'item_service';

    protected $fillable = [
        'order_layanan_id',
        'nama_item',
        'harga',
    ];

    public function orderLayanan()
    {
        return $this->belongsTo(OrderLayanan::class, 'order_layanan_id');
    }
}
