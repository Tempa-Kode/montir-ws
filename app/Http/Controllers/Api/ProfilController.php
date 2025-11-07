<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

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
}
