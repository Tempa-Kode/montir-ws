<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Bengkel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MontirBengkelController extends Controller
{
    /*
     * Menambahkan montir bengkel
     */
    public function tambahMontirBengkel(Request $request)
    {
        $validasi = $request->validate([
            'nama' => 'required|string|max:50',
            'alamat' => 'required|string',
            'no_telp' => 'required|string|max:15',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $validasi['password'] = bcrypt($validasi['password']);
        $validasi['role'] = User::ROLE_MONTIR;

        $user = $request->user();
        $bengkel = Bengkel::where('user_id', $user->id)->first();
        if (!$bengkel) {
            return response()->json([
                'status' => false,
                'message' => 'Bengkel tidak ditemukan',
            ], 404);
        }

        DB::beginTransaction();
        try {
            $montirUser = User::create($validasi);
            $bengkel->montirs()->create([
                'bengkel_id' => $bengkel->id,
                'user_id' => $montirUser->id,
            ]);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Montir bengkel berhasil ditambahkan',
                'data' => $montirUser
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Penambahan montir gagal',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
