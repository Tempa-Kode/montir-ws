<?php

namespace App\Http\Controllers\Api;

use App\Models\Bengkel;
use Illuminate\Http\Request;
use App\Models\LayananBengkel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PublicController extends Controller
{
    public function cariBengkelTedekatBerdasarkanJenisLayanan(Request $request)
    {
        $validasi = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'jenis_layanan' => 'required|string',
        ]);

        $latitude = $validasi['latitude'];
        $longitude = $validasi['longitude'];
        $jenisLayanan = $validasi['jenis_layanan'];

        // Mencari bengkel yang memiliki layanan sesuai dengan jenis layanan yang dicari
        // dan menghitung jarak menggunakan formula Haversine
        $bengkelList = Bengkel::select(
            'bengkel.*',
            DB::raw("
                (6371 * acos(
                    cos(radians($latitude))
                    * cos(radians(latitude))
                    * cos(radians(longitude) - radians($longitude))
                    + sin(radians($latitude))
                    * sin(radians(latitude))
                )) AS jarak
            ")
        )
        ->whereHas('layananBengkel', function($query) use ($jenisLayanan) {
            $query->where('jenis_layanan', 'LIKE', '%' . $jenisLayanan . '%');
        })
        ->where('verifikasi', 1)
        ->with(['layananBengkel' => function($query) use ($jenisLayanan) {
            $query->where('jenis_layanan', 'LIKE', '%' . $jenisLayanan . '%');
        }])
        ->orderBy('jarak', 'ASC')
        ->limit(10)
        ->get();

        // Format response dengan informasi jarak
        $data = $bengkelList->map(function($bengkel) {
            return [
                'id' => $bengkel->id,
                'nama' => $bengkel->nama,
                'alamat' => $bengkel->alamat,
                'latitude' => $bengkel->latitude,
                'longitude' => $bengkel->longitude,
                'foto' => $bengkel->foto ? asset($bengkel->foto) : null,
                'jarak' => round($bengkel->jarak, 2) . ' km',
                'jarak_meter' => round($bengkel->jarak * 1000),
                'layanan' => $bengkel->layananBengkel->pluck('jenis_layanan'),
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Data bengkel berhasil ditemukan',
            'data' => $data,
            'total' => $data->count(),
        ], 200);
    }

    /*
    * Detail Bengkel
    */
    public function detailBengkel($id)
    {
        $bengkel = Bengkel::with('layananBengkel')->find($id);
        if (!$bengkel) {
            return response()->json([
                'status' => false,
                'message' => 'Bengkel tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Detail bengkel ditemukan',
            'data' => [
                'bengkel' => [
                    'id' => $bengkel->id,
                    'nama' => $bengkel->nama,
                    'alamat' => $bengkel->alamat,
                    'latitude' => $bengkel->latitude,
                    'longitude' => $bengkel->longitude,
                    'foto' => $bengkel->foto ? asset($bengkel->foto) : null,
                ],
                'layanan' => $bengkel->layananBengkel,
            ],
        ], 200);
    }
}
