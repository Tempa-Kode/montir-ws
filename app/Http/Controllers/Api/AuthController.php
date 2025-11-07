<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
            $user = User::create($validasi);
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

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $data = [
                'id' => $user->id,
                'nama' => $user->nama,
                'alamat' => $user->alamat,
                'no_telp' => $user->no_telp,
                'email' => $user->email,
                'role' => $user->role,
                'foto' => $user->foto,
            ];

            if ($user->role === User::ROLE_BENGKEL) {
                $data['bengkel'] = $user->bengkel;
            }

            return response()->json([
                'status' => true,
                'message' => 'Login berhasil',
                'data' => $data,
                'token' => $user->createToken('auth_token')->plainTextToken,
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Login gagal',
        ], 401);
    }
}
