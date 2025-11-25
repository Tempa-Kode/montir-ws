<?php

namespace App\Http\Controllers\Api;

use App\Models\OrderLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

/**
 * @tags Montir Layanan
 */
class MontirLayananController extends Controller
{
    /**
     * List Order Layanan Montir
     *
     * Mendapatkan daftar order layanan yang ditugaskan kepada montir berdasarkan montir ID
     *
     * @urlParam montir_id integer required ID montir. Example: 1
     *
     * @response 200 {
     *   "status": true,
     *   "message": "Data order layanan montir berhasil diambil",
     *   "data": [
     *     {
     *       "id": 1,
     *       "status": "kelokasi",
     *       "pelanggan": {
     *         "id": 5,
     *         "nama": "John Doe",
     *         "no_telp": "081234567890"
     *       },
     *       "layanan_bengkel": {
     *         "jenis_layanan": "Ganti Oli"
     *       }
     *     }
     *   ],
     *   "total": 1
     * }
     */
    public function getOrderLayananByMontir($montirId)
    {
        $orders = OrderLayanan::where('montir_id', $montirId)
            ->with([
                'pelanggan',
                'layananBengkel.bengkel',
                'itemService',
                'ulasanRating'
            ])
            ->orderBy('created_at', 'DESC')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Data order layanan montir berhasil diambil',
            'data' => $orders,
            'total' => $orders->count()
        ], 200);
    }

    /**
     * Update Status Menuju Lokasi
     *
     * Montir mengupdate status order menjadi "kelokasi" ketika menuju ke lokasi pelanggan
     *
     * @urlParam orderLayananId integer required ID order layanan. Example: 1
     *
     * @response 200 {
     *   "status": true,
     *   "message": "Status layanan diperbarui ke 'kelokasi'",
     *   "data": {
     *     "id": 1,
     *     "status": "kelokasi"
     *   }
     * }
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

     /**
     * Update Status Mengerjakan
     *
     * Montir mengupdate status order menjadi "kerjakan" ketika mulai mengerjakan service
     *
     * @urlParam orderLayananId integer required ID order layanan. Example: 1
     *
     * @response 200 {
     *   "status": true,
     *   "message": "Status layanan diperbarui ke 'kerjakan'",
     *   "data": {
     *     "id": 1,
     *     "status": "kerjakan"
     *   }
     * }
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

     /**
     * Menyelesaikan Pekerjaan
     *
     * Montir menyelesaikan pekerjaan, update status menjadi "selesai" dan menginput item service beserta harga
     *
     * @urlParam orderLayananId integer required ID order layanan. Example: 1
     * @bodyParam harga_layanan numeric required Harga total layanan. Example: 150000
     * @bodyParam item_service array required Array berisi item service. Example: [{"nama_item": "Oli Mesin", "harga": 120000}]
     * @bodyParam item_service.*.nama_item string required Nama item service. Example: Oli Mesin
     * @bodyParam item_service.*.harga numeric required Harga item. Example: 120000
     *
     * @response 200 {
     *   "status": true,
     *   "message": "Status layanan diperbarui ke 'selesai' dan item service ditambahkan",
     *   "data": {
     *     "id": 1,
     *     "status": "selesai",
     *     "harga_layanan": 150000,
     *     "item_service": [
     *       {"id": 1, "nama_item": "Oli Mesin", "harga": 120000}
     *     ]
     *   }
     * }
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
            $orderLayanan->status = 'pembayaran';
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
