<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class ProfilController extends Controller
{
    public function getProfil(Request $request)
    {
        $userId = $request->user();
        $user = User::with(['bengkel', 'montir.bengkel'])->where('id', $userId->id)->first();

        if ($user->role === User::ROLE_BENGKEL) {
            $bengkel = $user->bengkel ?? null;
            return response()->json([
                'status' => true,
                'message' => 'Data profil bengkel berhasil diambil',
                'data' => [
                    'user' => $user->makeHidden('bengkel', 'montir'),
                    'bengkel' => $bengkel
                ]
            ], 200);
        } elseif ($user->role === User::ROLE_MONTIR) {
            $montir = $user->montir->bengkel ?? null;
            return response()->json([
                'status' => true,
                'message' => 'Data profil montir berhasil diambil',
                'data' => [
                    'user' => $user->makeHidden('bengkel', 'montir'),
                    'bengkel' => $montir
                ]
            ], 200);
        }

        return response()->json([
            'status' => true,
            'message' => 'Data profil berhasil diambil',
            'data' => [
                'user' => $user
            ]
        ], 200);
    }

    /**
     * Update user profile
     */
    public function updateProfil(Request $request)
    {
        $user = $request->user();
        $validasi = $request->validate([
            'nama' => 'required|string|max:50',
            'alamat' => 'required|string',
            'no_telp' => 'required|string|max:15',
            'email' => 'required|string|email|max:50|unique:users,email,' . $user->id,
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:pelanggan,bengkel',
        ]);

        DB::beginTransaction();
        try{
            if (isset($validasi['password'])) {
                $validasi['password'] = bcrypt($validasi['password']);
            }

            $user->update($validasi);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Profil berhasil diperbarui',
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui profil: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Profil gagal diperbarui',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
