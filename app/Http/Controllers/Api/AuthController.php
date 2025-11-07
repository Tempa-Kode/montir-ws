<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validasi = $request->validate([
            'nama' => 'required|string|max:50',
            'alamat' => 'required|string',
            'no_telp' => 'required|string|max:15',
            'email' => 'required|string|email|max:50|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:pelanggan,bengkel',
        ]);

        $validasi['password'] = bcrypt($validasi['password']);
        DB::beginTransaction();
        try {
            $user = \App\Models\User::create($validasi);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Pendaftaran berhasil',
                'data' => [
                    'id' => $user->id,
                    'nama' => $user->nama,
                    'alamat' => $user->alamat,
                    'no_telp' => $user->no_telp,
                    'email' => $user->email,
                    'role' => $user->role,
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Pendaftaran gagal',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
