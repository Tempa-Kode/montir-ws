<?php

namespace App\Http\Controllers\Api;

use App\Models\OrderLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MontirLayananController extends Controller
{
    /*
    * Montir : menuju kelokasi, dan memberbaharui status "kelokasi"
     */
    public function menujuKelokasi($orderLayananId)
    {
        $orderLayanan = OrderLayanan::find($orderLayananId);
        if (!$orderLayanan) {
            return response()->json([
                'status' => false,
                'message' => 'Layanan order tidak ditemukan'
            ], 404);
        }

        $orderLayanan->status = 'kelokasi';
        $orderLayanan->save();
        return response()->json([
            'status' => true,
            'message' => 'Status layanan diperbarui ke "kelokasi"',
            'data' => $orderLayanan
        ], 200);
    }

     /*
    * Montir : mengerjakan service, dan memberbaharui status "dikerjakan"
     */
    public function mengerjakanService($orderLayananId)
    {
        $orderLayanan = OrderLayanan::find($orderLayananId);
        if (!$orderLayanan) {
            return response()->json([
                'status' => false,
                'message' => 'Layanan order tidak ditemukan'
            ], 404);
        }

        $orderLayanan->status = 'kerjakan';
        $orderLayanan->save();
        return response()->json([
            'status' => true,
            'message' => 'Status layanan diperbarui ke "kerjakan"',
            'data' => $orderLayanan
        ], 200);
    }

     /*
    * Montir : menyelesaikan pekerjaan, dan memberbaharui status "selesai" dan menginputkan item service beserta harga layanan
     */
    public function menyelesaikanPekerjaan($orderLayananId, Request $request)
    {
        $validasi = $request->validate([
            'harga_layanan' => 'required|numeric|min:0',
            'item_service' => 'required|array|min:1',
            'item_service.*.nama_item' => 'required|string',
            'item_service.*.harga' => 'required|numeric|min:0',
        ]);

        $orderLayanan = OrderLayanan::find($orderLayananId);
        if (!$orderLayanan) {
            return response()->json([
                'status' => false,
                'message' => 'Layanan order tidak ditemukan'
            ], 404);
        }

        DB::beginTransaction();
        try {
            $orderLayanan->harga_layanan = $validasi['harga_layanan'];
            // Update status order
            $orderLayanan->status = 'selesai';
            $orderLayanan->save();

            // Simpan semua item service
            foreach ($validasi['item_service'] as $item) {
                $orderLayanan->itemService()->create([
                    'nama_item' => $item['nama_item'],
                    'harga' => $item['harga'],
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Status layanan diperbarui ke "selesai" dan item service ditambahkan',
                'data' => $orderLayanan->load('itemService')
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyelesaikan pekerjaan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
