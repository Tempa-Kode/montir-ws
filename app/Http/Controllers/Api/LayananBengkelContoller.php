<?php

namespace App\Http\Controllers\Api;

use App\Models\Bengkel;
use Illuminate\Http\Request;
use App\Models\LayananBengkel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LayananBengkelContoller extends Controller
{
    /*
    * Menampilkan daftar layanan bengkel yang disediakan
    */
    public function index(Request $request)
    {
        $user = $request->user();
        $bengkel = Bengkel::where('user_id', $user->id)->first();

        if (!$bengkel) {
            return response()->json([
                'status' => false,
                'message' => 'Bengkel tidak ditemukan',
            ], 404);
        }

        $layanan = $bengkel->layananBengkel()->get();

        return response()->json([
            'status' => true,
            'message' => 'Daftar layanan bengkel berhasil diambil',
            'data' => $layanan
        ], 200);
    }

    /*
    * Bengkel mendaftarkan layanan bengkel yang disediakan
    */
    public function daftarLayananBengkel(Request $request)
    {
        $validasi = $request->validate([
            'jenis_layanan' => 'required|array',
            'jenis_layanan.*' => 'string|max:100',
        ]);

        $user = $request->user();
        $bengkel = Bengkel::where('user_id', $user->id)->first();

        if (!$bengkel) {
            return response()->json([
                'status' => false,
                'message' => 'Bengkel tidak ditemukan',
            ], 404);
        }

        foreach ($validasi['jenis_layanan'] as $layanan) {
            $bengkel->layananBengkel()->create([
                'bengkel_id' => $bengkel->id,
                'jenis_layanan' => $layanan,
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Layanan bengkel berhasil didaftarkan',
            'data' => [
                'bengkel' => [
                    'id' => $bengkel->id,
                    'nama' => $bengkel->nama,
                    'alamat' => $bengkel->alamat,
                    'latitude' => $bengkel->latitude,
                    'longitude' => $bengkel->longitude,
                    'foto' => $bengkel->foto,
                ],
                'layanan' => $bengkel->layananBengkel()->get()
            ]
        ], 200);
    }

    /*
    * Bengkel mengupdate layanan bengkel yang disediakan
    */
   public function updateLayananBengkel(Request $request, $id)
    {
        $validasi = $request->validate([
            'jenis_layanan' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $layanan = LayananBengkel::findOrFail($id);
            $layanan->jenis_layanan = $validasi['jenis_layanan'];
            $layanan->save();

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Layanan bengkel berhasil diupdate',
                'data' => $layanan
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Update layanan bengkel gagal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /*
    * Bengkel menghapus layanan bengkel yang disediakan
    */
   public function hapusLayananBengkel($id)
    {
        DB::beginTransaction();
        try {
            $layanan = LayananBengkel::findOrFail($id);
            $layanan->delete();
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Layanan bengkel berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Hapus layanan bengkel gagal',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
