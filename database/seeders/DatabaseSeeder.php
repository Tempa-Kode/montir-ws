<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'nama' => 'Superadmin',
            'alamat' => 'Jl. Admin No. 1',
            'no_telp' => '081234567890',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Pelanggan
        User::create([
            'nama' => 'Pelanggan',
            'alamat' => 'Jl. Pelanggan No. 1',
            'no_telp' => '089876543210',
            'email' => 'pelanggan@example.com',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
        ]);

        // Montir
        User::create([
            'nama' => 'Montir',
            'alamat' => 'Jl. Montir No. 1',
            'no_telp' => '087654321098',
            'email' => 'montir@example.com',
            'password' => bcrypt('password'),
            'role' => 'montir',
        ]);

        // Bengkel
        User::create([
            'nama' => 'Bengkel',
            'alamat' => 'Jl. Bengkel No. 1',
            'no_telp' => '086543210987',
            'email' => 'bengkel@example.com',
            'password' => bcrypt('password'),
            'role' => 'bengkel',
        ]);
    }
}
