<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'alamat',
        'no_telp',
        'email',
        'password',
        'role',
        'foto',
    ];

    public const ROLE_ADMIN = 'admin';
    public const ROLE_BENGKEL = 'bengkel';
    public const ROLE_PELANGGAN = 'pelanggan';
    public const ROLE_MONTIR = 'montir';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function bengkel()
    {
        return $this->hasOne(Bengkel::class);
    }

    public function montir()
    {
        return $this->hasMany(Montir::class);
    }
}
