<?php

namespace App\Http\Controllers\Api;

use App\Models\Bengkel;
use Illuminate\Http\Request;
use App\Models\LayananBengkel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Montir;

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

    // mengambil data rating montir pada bengkel tertentu, untuk dijadikan bar chart pada dashboard bengkel di aplikasi mobile. untuk melihat seberapa banyak montir yang memiliki rating tertentu (1-5) pada bengkel tersebut.
    // agar dapat mengambil keputusan terjadap kinerja montir.
    public function getRatingMontirBengkel(Request $request)
    {
        $user = $request->user();
        if($user->role === 'bengkel'){
            $bengkel = Bengkel::where('user_id', $user->id)->first();
        } else {
            $montir = Montir::where('user_id', $user->id)->first();
            $bengkel = $montir->bengkel;
        }

        if (!$bengkel) {
            return response()->json([
                'status' => false,
                'message' => 'Bengkel tidak ditemukan',
            ], 404);
        }

        $montirRatings = DB::table('montir')
            ->join('users', 'montir.user_id', '=', 'users.id')
            ->leftJoin('ulasan_rating_montir', 'montir.id', '=', 'ulasan_rating_montir.montir_id') // Corrected join condition
            ->where('montir.bengkel_id', $bengkel->id)
            ->select(
                'users.nama as nama_montir',
                DB::raw('COALESCE(AVG(ulasan_rating_montir.rating), 0) as rating_rata_rata')
            )
            ->groupBy('montir.id', 'users.nama', 'montir.user_id') // Group by montir.id for distinct mechanics
            ->orderBy('rating_rata_rata', 'desc') // Mengurutkan berdasarkan rating dari tertinggi
            ->get();

        // Konversi hasil rating ke float dengan satu desimal
        $montirRatings = $montirRatings->map(function ($montir) {
            $montir->rating_rata_rata = round((float)$montir->rating_rata_rata, 1);
            return $montir;
        });

        return response()->json([
            'status' => true,
            'message' => 'Data rating montir berhasil diambil',
            'data' => $montirRatings
        ], 200);
    }
}
