<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bengkel extends Model
{
    public $table = 'bengkel';
    public $fillable = [
        'user_id',
        'nama',
        'alamat',
        'latitude',
        'longitude',
        'verifikasi',
        'alasan_penolakan',
        'foto',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function montirs()
    {
        return $this->hasMany(Montir::class);
    }

    public function layananBengkel()
    {
        return $this->hasMany(LayananBengkel::class, 'bengkel_id');
    }

    public function ulasanBengkel()
    {
        return $this->hasMany(UlasanRating::class, 'bengkel_id');
    }

    public function ulasanRatings()
    {
        return $this->hasMany(UlasanRating::class, 'bengkel_id');
    }
}
