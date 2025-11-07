<?php

namespace App\Http\Controllers\Api;

use App\Models\Bengkel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class BengkelController extends Controller
{
    /**
     * Menyimpan detail data bengkel
     */
    public function simpanDataBengkel(Request $request)
    {
        $validasi = $request->validate([
            'nama' => 'required|string|max:50',
            'alamat' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'foto' => 'nullable|image|max:2048',
        ]);

        $user = $request->user();
        DB::beginTransaction();
        try {
            $validasi['user_id'] = $user->id;

            // Handle upload foto
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                // Pastikan folder uploads ada
                $uploadPath = public_path('uploads/bengkel');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                // Pindahkan file ke public/uploads/bengkel
                $file->move($uploadPath, $filename);

                // Simpan path relatif ke database
                $validasi['foto'] = 'uploads/bengkel/' . $filename;
            }
            $bengkel = Bengkel::create($validasi);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Data bengkel berhasil disimpan',
                'data' => $bengkel
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Penyimpanan data bengkel gagal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cek status validasi bengkel
     */
    public function cekStatusValidasiBengkel(Request $request)
    {
        $user = $request->user();
        $bengkel = Bengkel::where('user_id', $user->id)->first();

        if (!$bengkel) {
            return response()->json([
                'status' => false,
                'message' => 'Bengkel tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Status validasi bengkel berhasil diambil',
            'data' => $bengkel
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
}
