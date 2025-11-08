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

    public function listOrderLayanan(Request $request)
    {
        $pelangganId = $request->user()->id;

        $orders = OrderLayanan::where('pelanggan_id', $pelangganId)
            ->with('layananBengkel.bengkel')
            ->orderBy('created_at', 'DESC')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $orders
        ], 200);
    }

    /*
     * Bengkel : menampilkan data order layanan dari bengkel
     */
    public function listOrderLayananBengkel(Request $request)
    {
        $bengkelId = $request->user()->bengkel->id;

        $orders = OrderLayanan::whereHas('layananBengkel', function($query) use ($bengkelId) {
                $query->where('bengkel_id', $bengkelId);
            })
            ->with('pelanggan', 'layananBengkel')
            ->orderBy('created_at', 'DESC')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $orders
        ], 200);
    }

    /*
    * Bengkel : menugaskan montir
    */
    public function assignMontirToOrderLayanan(Request $request, $orderLayananId)
    {
        $validasi = $request->validate([
            'montir_id' => 'required|integer|exists:montir,id',
        ]);

        $orderLayanan = OrderLayanan::with(
                'montir',
                'montir.user',
                'montir.bengkel',
                'layananBengkel'
            )->find($orderLayananId);
        if (!$orderLayanan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order layanan tidak ditemukan'
            ], 404);
        }

        $orderLayanan->montir_id = $validasi['montir_id'];
        $orderLayanan->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Montir berhasil ditugaskan ke order layanan',
            'data' => $orderLayanan
        ], 200);
    }
}
