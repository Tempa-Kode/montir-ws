<?php

namespace App\Http\Controllers\Api;

use App\Models\OrderLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class OrderLayananController extends Controller
{
    public function buatOrderLayanan(Request $request)
    {
        $validasi = $request->validate([
            'layanan_bengkel_id' => 'required|integer|exists:layanan_bengkel,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $validasi['pelanggan_id'] = $request->user()->id;

        DB::beginTransaction();
        try {
            $orderLayanan = OrderLayanan::create($validasi);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Order layanan berhasil dibuat',
                'data' => $orderLayanan
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat order layanan: ' . $e->getMessage()
            ], 500);
        }
    }
}
