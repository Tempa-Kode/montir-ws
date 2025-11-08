<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

/**
 * @tags Profil Management
 */
class ProfilController extends Controller
{
    /**
     * Get Profil User
     * 
     * Mendapatkan informasi profil user yang sedang login
     */
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
     * Update Profil User
     * 
     * Memperbarui data profil user (nama, alamat, no telepon, email)
     */
    public function updateProfil(Request $request)
    {
        $user = $request->user();
        $validasi = $request->validate([
            'nama' => 'required|string|max:50',
            'alamat' => 'required|string',
            'no_telp' => 'required|string|max:15',
            'email' => 'required|string|email|max:50|unique:users,email,' . $user->id,
        ]);

        DB::beginTransaction();
        try{
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

    /**
     * Update Foto Profil
     * 
     * Mengupdate foto profil user
     * 
     * @bodyParam foto file required File foto profil (max: 2MB, format: jpg, png, jpeg)
     */
    public function updateFotoProfil(Request $request)
    {
        try {
            $validasi = $request->validate([
                'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        }

        $user = $request->user();

        DB::beginTransaction();
        try {
            // Hapus foto lama jika ada
            if ($user->foto && file_exists(public_path($user->foto))) {
                unlink(public_path($user->foto));
            }

            // Handle upload foto
            $file = $request->file('foto');
            $filename = 'profil_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Pastikan folder uploads ada
            $uploadPath = public_path('uploads/profil');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Pindahkan file ke public/uploads/profil
            $file->move($uploadPath, $filename);

            // Simpan path relatif ke database
            $user->foto = 'uploads/profil/' . $filename;
            $user->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Foto profil berhasil diperbarui',
                'data' => $user
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            // Hapus file jika ada error
            if (isset($filename) && file_exists(public_path('uploads/profil/' . $filename))) {
                unlink(public_path('uploads/profil/' . $filename));
            }

            Log::error('Gagal mengupdate foto profil: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengupdate foto profil',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Change Password
     * 
     * Mengubah password user
     */
    public function changePassword(Request $request)
    {
        try {
            $validasi = $request->validate([
                'password_lama' => 'required|string',
                'password_baru' => 'required|string|min:8|confirmed',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        }

        $user = $request->user();

        // Cek password lama
        if (!Hash::check($validasi['password_lama'], $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Password lama tidak sesuai'
            ], 400);
        }

        DB::beginTransaction();
        try {
            $user->password = Hash::make($validasi['password_baru']);
            $user->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Password berhasil diubah'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal mengubah password: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengubah password',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
