<?php

namespace App\Http\Controllers\Api;

use App\Models\OrderLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\UlasanRating;

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
    * Melihat detail order layanan
    */
    public function detailOrderLayanan($orderLayananId)
    {
        $orderLayanan = OrderLayanan::with(
                'pelanggan',
                'montir',
                'montir.user',
                'montir.bengkel',
                'layananBengkel',
                'itemService'
            )->find($orderLayananId);

        if (!$orderLayanan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order layanan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $orderLayanan
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

    /*
    * Pelanggan : upload bukti pembayaran
    */
    public function uploadBuktiPembayaran(Request $request, $orderLayananId)
    {
        try {
            $validasi = $request->validate([
                'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        }

        $orderLayanan = OrderLayanan::find($orderLayananId);
        if (!$orderLayanan) {
            return response()->json([
                'status' => false,
                'message' => 'Order layanan tidak ditemukan'
            ], 404);
        }

        DB::beginTransaction();
        try {
            // Hapus file lama jika ada
            if ($orderLayanan->bukti_bayar && file_exists(public_path($orderLayanan->bukti_bayar))) {
                unlink(public_path($orderLayanan->bukti_bayar));
            }

            // Handle upload foto
            $file = $request->file('bukti_bayar');
            $filename = 'bukti_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Pastikan folder uploads ada
            $uploadPath = public_path('uploads/bukti_pembayaran');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Pindahkan file ke public/uploads/bukti_pembayaran
            $file->move($uploadPath, $filename);

            // Simpan path relatif ke database
            $orderLayanan->bukti_bayar = 'uploads/bukti_pembayaran/' . $filename;
            $orderLayanan->status_pembayaran = 'belum-lunas';
            $orderLayanan->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Bukti pembayaran berhasil diupload',
                'data' => $orderLayanan
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            // Hapus file jika ada error
            if (isset($filename) && file_exists(public_path('uploads/bukti_pembayaran/' . $filename))) {
                unlink(public_path('uploads/bukti_pembayaran/' . $filename));
            }

            return response()->json([
                'status' => false,
                'message' => 'Gagal mengupload bukti pembayaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /*
    * Bengkel : menerima pembayaran dan mengubah status pembayaran
     */
    public function validatePembayaranOrderLayanan(Request $request, $orderLayananId)
    {
        $orderLayanan = OrderLayanan::find($orderLayananId);
        if (!$orderLayanan) {
            return response()->json([
                'status' => false,
                'message' => 'Order layanan tidak ditemukan'
            ], 404);
        }

        // Validasi: hanya bisa upload jika status selesai
        if ($orderLayanan->status !== 'selesai') {
            return response()->json([
                'status' => false,
                'message' => 'Bukti pembayaran hanya bisa diupload setelah pekerjaan selesai'
            ], 400);
        }

        // Validasi: cek apakah user adalah pemilik order
        if ($orderLayanan->pelanggan_id !== $request->user()->id) {
            return response()->json([
                'status' => false,
                'message' => 'Anda tidak memiliki akses untuk order ini'
            ], 403);
        }

        $orderLayanan->status_pembayaran = 'lunas';
        $orderLayanan->save();

        return response()->json([
            'status' => true,
            'message' => 'Pembayaran berhasil divalidasi',
            'data' => $orderLayanan
        ], 200);
    }

    /*
    * Pelanggan : memberikan ulasan dan rating
     */
    public function berikanUlasanDanRating(Request $request, $orderLayananId)
    {
        $validasi = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'nullable|string|max:1000',
        ]);

        $orderLayanan = OrderLayanan::find($orderLayananId);
        if (!$orderLayanan) {
            return response()->json([
                'status' => false,
                'message' => 'Order layanan tidak ditemukan'
            ], 404);
        }

        $ulasanRating = UlasanRating::updateOrCreate(
            ['order_layanan_id' => $orderLayananId],
            [
                'pelanggan_id' => $request->user()->id,
                'bengkel_id' => $orderLayanan->layananBengkel->bengkel_id,
                'rating' => $validasi['rating'],
                'ulasan' => $validasi['ulasan'] ?? null,
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Ulasan dan rating berhasil disimpan',
            'data' => $ulasanRating
        ], 200);
    }
}
